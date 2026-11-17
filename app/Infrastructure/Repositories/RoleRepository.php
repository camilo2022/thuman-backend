<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Authorization\Role\Contracts\RoleContract;
use App\Models\Role as RoleModel;
use App\Domain\Authorization\Role\Role;
use App\Domain\Authorization\Role\Values\RolePermissionId;
use App\Domain\Authorization\Role\Values\RoleCreatedAt;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Domain\Authorization\Role\Values\RoleName;
use App\Domain\Authorization\Role\Values\RoleDescription;
use App\Domain\Authorization\Role\Values\RoleRelationships;
use App\Domain\Authorization\Role\Values\RoleTitle;
use App\Domain\Authorization\Role\Values\RoleUpdatedAt;
use App\Http\Requests\Authorization\Role\RoleFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RoleRepository implements RoleContract
{
    private $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $roles = $this->roleModel
                ->with(['permissions', 'submodules'])
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $roles->paginate($perPage ?? $roles->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los roles: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los roles: ' . $e->getMessage());
        }
    }

    public function find(RoleFindRequest $request, RoleId $roleId): ?Role
    {
        try {
            $model = $this->roleModel
                ->with(['permissions', 'submodules'])
                ->when($roleId->value(), function ($query) use ($roleId) {
                    $query->where('id', $roleId->value());
                })
                ->when(!$roleId->value(), function ($query) use ($request) {
                    $query->when($request->filled('name'), function ($query) use ($request) {
                        $query->where('name', $request->input('name'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El rol no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el rol: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el rol: ' . $e->getMessage());
        }
    }

    public function store(Role $role): Role
    {
        try {
            $model = $this->roleModel
                ->create($this->mapToModelData($role));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el rol: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el rol: ' . $e->getMessage());
        }
    }

    public function update(Role $role, RoleId $roleId): Role
    {
        try {
            $model = tap($this->roleModel->findOrFail($roleId->value()))
                ->update($this->mapToModelData($role))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El rol no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el rol: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el rol: ' . $e->getMessage());
        }
    }

    public function toggle(Role $role, RoleId $roleId): Role
    {
        try {
            $model =$this->roleModel->findOrFail($roleId->value());

            $model->permissions()->toggle(array_filter([
                $role->permissionId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El rol no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el rol: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el rol: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Role
    {
        return new Role(
            new RoleId($model->id),
            new RoleName($model->name),
            new RoleTitle($model->title),
            new RoleDescription($model->description),
            new RolePermissionId(),
            new RoleCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new RoleUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new RoleRelationships($model->permissions)
        );
    }

    private function mapToModelData(Role $role): array
    {
        $array = [];

        if ($role->name()->value()) {
            $array['name'] = $role->name()->value();
        }

        if ($role->title()->value()) {
            $array['title'] = $role->title()->value();
        }

        if ($role->description()->value()) {
            $array['description'] = $role->description()->value();
        }

        return $array;
    }
}
