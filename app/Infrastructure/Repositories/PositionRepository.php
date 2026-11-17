<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Position\Contracts\PositionContract;
use App\Models\Position as PositionModel;
use App\Models\Permission as PermissionModel;
use App\Domain\Position\Position;
use App\Domain\Position\Values\PositionAreaId;
use App\Domain\Position\Values\PositionCreatedAt;
use App\Domain\Position\Values\PositionDeletedAt;
use App\Domain\Position\Values\PositionDescription;
use App\Domain\Position\Values\PositionId;
use App\Domain\Position\Values\PositionName;
use App\Domain\Position\Values\PositionPermissionId;
use App\Domain\Position\Values\PositionProfileCategoryId;
use App\Domain\Position\Values\PositionRelationships;
use App\Domain\Position\Values\PositionSettings;
use App\Domain\Position\Values\PositionUpdatedAt;
use App\Http\Requests\Position\PositionAllRequest;
use App\Models\Addendum;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Vacant;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;


final class PositionRepository implements PositionContract
{
    private $positionModel;
    private $permissionModel;

    public function __construct()
    {
        $this->positionModel = new PositionModel;
        $this->permissionModel = new PermissionModel;
    }

    public function all(string|null $search = null, int|null $perPage, PositionAllRequest $request): LengthAwarePaginator
    {
        try {
            $positions = $this->positionModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['area', 'profile_category', 'roles', 'permissions'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('area_id'), fn($q) => $q->where('area_id', $request->integer('area_id')))
                ->when($request->filled('profile_category_id'), fn($q) => $q->where('profile_category_id', $request->integer('profile_category_id')))
                ->when($request->filled('employee_id'), function ($q) use ($request) {
                    $q->where('area_id', function ($sub) use ($request) {
                        $sub->select('positions.area_id')->from('employees')->join('positions', 'positions.id', '=', 'employees.position_id')->where('employees.id', $request->integer('employee_id'))->limit(1);
                    });
                })
                ->orderBy('id', 'asc');

            return $positions->paginate($perPage ?? $positions->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los perfiles de cargo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los perfiles de cargo: ' . $e->getMessage());
        }
    }

    public function find(PositionId $positionId): ?Position
    {
        try {
            $model = $this->positionModel->withTrashed()
                ->with(['area', 'profile_category', 'roles', 'permissions'])
                ->findOrFail($positionId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El perfil de cargo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el perfil de cargo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el perfil de cargo: ' . $e->getMessage());
        }
    }

    public function store(Position $position): Position
    {
        try {
            $model = $this->positionModel
                ->create($this->mapToModelData($position));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el perfil de cargo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el perfil de cargo: ' . $e->getMessage());
        }
    }

    public function update(Position $position, PositionId $positionId): Position
    {
        try {
            $model = tap($this->positionModel->findOrFail($positionId->value()))
                ->update($this->mapToModelData($position))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El perfil de cargo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el perfil de cargo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el perfil de cargo: ' . $e->getMessage());
        }
    }

    public function toggle(Position $position, PositionId $positionId): Position
    {
        try {
            $model = $this->positionModel->withTrashed()->findOrFail($positionId->value());

            $permission = $this->permissionModel->with('roles.permissions')->findOrFail($position->permissionId()->value());

            if ($model->hasDirectPermission($permission)) {
                $model->revokePermissionTo($permission);
            } else {
                $model->givePermissionTo($permission);
            }

            $model->load('permissions', 'roles');

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
            throw new HttpException(404, 'El perfil de cargo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al autorizar el perfil de cargo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al autorizar el perfil de cargo: ' . $e->getMessage());
        }
    }

    public function delete(PositionId $positionId): Position
    {
        try {

            $model = $this->positionModel->findOrFail($positionId->value());

            $isUsed = Vacant::where('position_id', $model->id)->exists() || Contract::where('position_id', $model->id)->exists()
                || Employee::where('position_id', $model->id)->exists() || Addendum::where('position_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El perfil de cargo no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el perfil de cargo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el perfil de cargo: ' . $e->getMessage());
        }
    }

    public function restore(PositionId $positionId): Position
    {
        try {
            $model = tap($this->positionModel->withTrashed()->findOrFail($positionId->value()), fn($position) => $position->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El perfil de cargo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el perfil de cargo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el perfil de cargo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Position
    {
        return new Position(
            new PositionId($model->id),
            new PositionAreaId($model->area_id),
            new PositionName($model->name),
            new PositionProfileCategoryId($model->profile_category_id),
            new PositionDescription($model->description),
            new PositionSettings($model->settings),
            new PositionPermissionId(),
            new PositionCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PositionUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PositionDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new PositionRelationships($model->area, $model->profile_category, $model->roles, $model->permissions)
        );
    }

    private function mapToModelData(Position $position): array
    {
        $array = [];

        if ($position->areaId()->value()) {
            $array['area_id'] = $position->areaId()->value();
        }

        if ($position->name()->value()) {
            $array['name'] = $position->name()->value();
        }

        if ($position->profileCategoryId()->value()) {
            $array['profile_category_id'] = $position->profileCategoryId()->value();
        }

        if ($position->description()->value()) {
            $array['description'] = $position->description()->value();
        }

        if ($position->settings()->value()) {
            $array['settings'] = $position->settings()->value();
        }

        return $array;
    }
}
