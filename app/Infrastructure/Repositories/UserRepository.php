<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\User\Contracts\UserContract;
use App\Models\User as UserModel;
use App\Models\Permission as PermissionModel;
use App\Domain\User\User;
use App\Domain\User\Values\UserCreatedAt;
use App\Domain\User\Values\UserDeletedAt;
use App\Domain\User\Values\UserId;
use App\Domain\User\Values\UserPassword;
use App\Domain\User\Values\UserPermissionId;
use App\Domain\User\Values\UserPersonId;
use App\Domain\User\Values\UserRelationships;
use App\Domain\User\Values\UserUpdatedAt;
use App\Http\Requests\User\UserFindRequest;
use App\Http\Requests\User\UserRecoveryEmailRequest;
use App\Http\Requests\User\UserRecoveryPhoneNumberRequest;
use App\Jobs\NotificationJob;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class UserRepository implements UserContract
{
    private $userModel;
    private $permissionModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->permissionModel = new PermissionModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $users = $this->userModel
                ->with(['person.employee.position.area', 'roles', 'permissions'])
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $users->paginate($perPage ?? $users->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los usuarios: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los usuarios: ' . $e->getMessage());
        }
    }

    public function find(UserFindRequest $request, UserId $userId): ?User
    {
        try {
            $model = $this->userModel
                ->with(['person.employee.position.area', 'roles', 'permissions'])
                ->when($userId->value(), function ($query) use ($userId) {
                    $query->where('id', $userId->value());
                })
                ->when(!$userId->value(), function ($query) use ($request) {
                    $query->when($request->filled('person_id'), function ($query) use ($request) {
                        $query->where('person_id', $request->integer('person_id'));
                    })
                    ->whereHas('person', function ($personQuery) use ($request) {
                        $personQuery->when($request->filled('document_number'), function ($query) use ($request) {
                            $query->where('document_number', $request->input('document_number'));
                        })
                        ->when($request->filled('phone_number'), function ($query) use ($request) {
                            $query->where('phone_number', $request->input('phone_number'));
                        })
                        ->when($request->filled('email'), function ($query) use ($request) {
                            $query->where('email', $request->input('email'));
                        });
                    })
                    ->when($request->filled('employee_id'), function ($query) use ($request) {
                        $query->whereHas('person.employee', function ($q) use ($request) {
                            $q->where('id', $request->integer('employee_id'));
                        });
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El usuario no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el usuario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el usuario: ' . $e->getMessage());
        }
    }

    public function store(User $user): User
    {
        try {
            $model = $this->userModel
                ->create($this->mapToModelData($user));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el usuario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el usuario: ' . $e->getMessage());
        }
    }

    public function update(User $user, UserId $userId): User
    {
        try {
            $model = tap($this->userModel->findOrFail($userId->value()))
                ->update($this->mapToModelData($user))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El usuario no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el usuario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function toggle(User $user, UserId $userId): User
    {
        try {
            $model = $this->userModel->with(['roles', 'permissions'])->withTrashed()->findOrFail($userId->value());

            $permission = $this->permissionModel->with('roles.permissions')->findOrFail($user->permissionId()->value());

            if ($model->hasDirectPermission($permission)) {
                $model->revokePermissionTo($permission);
            } else {
                $model->givePermissionTo($permission);
            }

            $model->load('roles', 'permissions');

            foreach ($permission->roles as $role) {
                $modelPermissionIds = $model->permissions->whereIn('id', $role->permissions->pluck('id'))->pluck('id');

                $hasPermissionFromRole = $modelPermissionIds->isNotEmpty();

                if ($hasPermissionFromRole) {
                    if (!$model->hasRole($role)) {
                        $model->assignRole($role);
                    }
                } else {
                    if ($model->hasRole($role)) {
                        $model->removeRole($role);
                    }
                }
            }

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El usuario no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al autorizar el usuario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al autorizar el usuario: ' . $e->getMessage());
        }
    }

    public function recoveryEmail(UserRecoveryEmailRequest $request): ?User
    {
        try {
            $model = $this->userModel
                ->with(['person' => ['document_type', 'document_issue_city.departament.country', 'birth_city.departament.country', 'dialing_country', 'city.departament.country']])
                ->whereHas('person', fn($query) => $query->where('document_number', $request->input('document_number')))->firstOrFail();

            if ($model->person->email !== $request->input('email')) {
                throw new HttpException(422, 'El correo electrónico no coincide con el número de documento.');
            }

            $password = Str::random(16);

            $data = (object) [
                'user' => $model,
                'password' => $password
            ];

            NotificationJob::dispatchSync(361, $data, [336], 'html');

            $model->password = $password;
            $user = $this->mapToEntity($model);

            $model = tap($this->userModel->findOrFail($model->id))
                ->update($this->mapToModelData($user))->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El usuario no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el usuario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar la notificación al usuario: ' . $e->getMessage());
        }
    }

    public function recoveryPhoneNumber(UserRecoveryPhoneNumberRequest $request): ?User
    {
        try {
            $model = $this->userModel
                ->with(['person' => ['document_type', 'document_issue_city.departament.country', 'birth_city.departament.country', 'dialing_country', 'city.departament.country']])
                ->whereHas('person', fn($query) => $query->where('document_number', $request->input('document_number')))->firstOrFail();

            if ($model->person->phone_number !== $request->input('phone_number')) {
                throw new HttpException(422, 'El número de teléfono no coincide con el número de documento.');
            }

            $password = Str::random(16);

            $data = (object) [
                'user' => $model,
                'password' => $password
            ];

            NotificationJob::dispatchSync(361, $data, [335], 'text');

            $model->password = $password;
            $user = $this->mapToEntity($model);

            $model = tap($this->userModel->findOrFail($model->id))
                ->update($this->mapToModelData($user))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El usuario no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el usuario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar la notificación al usuario: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): User
    {
        return new User(
            new UserId($model->id),
            new UserPersonId($model->person?->id ?? $model->person_id),
            new UserPassword($model->password),
            new UserPermissionId(),
            new UserCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new UserUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new UserDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new UserRelationships($model->person, $model->roles, $model->permissions)
        );
    }

    private function mapToModelData(User $user): array
    {
        $array = [];

        if ($user->personId()->value()) {
            $array['person_id'] = $user->personId()->value();
        }

        if ($user->password()->value()) {
            $array['password'] = $user->password()->value();
        }

        return $array;
    }
}
