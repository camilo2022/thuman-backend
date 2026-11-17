<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Authorization\Permission\Contracts\PermissionContract;
use App\Models\Permission as PermissionModel;
use App\Domain\Authorization\Permission\Permission;
use App\Domain\Authorization\Permission\Values\PermissionCreatedAt;
use App\Domain\Authorization\Permission\Values\PermissionId;
use App\Domain\Authorization\Permission\Values\PermissionName;
use App\Domain\Authorization\Permission\Values\PermissionDescription;
use App\Domain\Authorization\Permission\Values\PermissionRelationships;
use App\Domain\Authorization\Permission\Values\PermissionTitle;
use App\Domain\Authorization\Permission\Values\PermissionUpdatedAt;
use App\Http\Requests\Authorization\Permission\PermissionAllRequest;
use App\Http\Requests\Authorization\Permission\PermissionFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PermissionRepository implements PermissionContract
{
    private $permissionModel;

    public function __construct()
    {
        $this->permissionModel = new PermissionModel;
    }

    public function all(string|null $search = null, int|null $perPage, PermissionAllRequest $request): LengthAwarePaginator
    {
        try {
            $permissions = $this->permissionModel
                ->with(['roles'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('role_id'), function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->whereHas('roles', fn ($r) => $r->where('id', $request->integer('role_id')))
                            ->orWhereDoesntHave('roles');
                    });
                })
                ->when($request->filled('permission_id'), function ($query) use ($request) {
                    $query->where('id', $request->integer('permission_id'));
                })
                ->orderBy('id', 'asc');

            return $permissions->paginate($perPage ?? $permissions->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los permisos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los permisos: ' . $e->getMessage());
        }
    }

    public function find(PermissionFindRequest $request, PermissionId $permissionId): ?Permission
    {
        try {
            $model = $this->permissionModel
                ->with(['roles'])
                ->when($permissionId->value(), function ($query) use ($permissionId) {
                    $query->where('id', $permissionId->value());
                })
                ->when(!$permissionId->value(), function ($query) use ($request) {
                    $query->when($request->filled('name'), function ($query) use ($request) {
                        $query->where('name', $request->input('name'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El perfil no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el permiso: ' . $e->getMessage());
        }
    }

    public function store(Permission $permission): Permission
    {
        try {
            $model = $this->permissionModel
                ->create($this->mapToModelData($permission));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el permiso: ' . $e->getMessage());
        }
    }

    public function update(Permission $permission, PermissionId $permissionId): Permission
    {
        try {
            $model = tap($this->permissionModel->findOrFail($permissionId->value()))
                ->update($this->mapToModelData($permission))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El permiso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el permiso: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Permission
    {
        return new Permission(
            new PermissionId($model->id),
            new PermissionName($model->name),
            new PermissionTitle($model->title),
            new PermissionDescription($model->description),
            new PermissionCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PermissionUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PermissionRelationships($model->roles)
        );
    }

    private function mapToModelData(Permission $permission): array
    {
        $array = [];

        if ($permission->name()->value()) {
            $array['name'] = $permission->name()->value();
        }

        if ($permission->title()->value()) {
            $array['title'] = $permission->title()->value();
        }

        if ($permission->description()->value()) {
            $array['description'] = $permission->description()->value();
        }

        return $array;
    }
}
