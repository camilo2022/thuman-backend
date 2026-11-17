<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Navigation\Module\Submodule\Contracts\SubmoduleContract;
use App\Domain\Navigation\Module\Submodule\Submodule;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleCreatedAt;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleDeletedAt;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleIcon;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleModelId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleModelType;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleName;
use App\Domain\Navigation\Module\Submodule\Values\SubmodulePermissionId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRelationships;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRoleId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRoute;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleType;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleUpdatedAt;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Http\Requests\Navigation\Module\Submodule\SubmoduleAllRequest;
use App\Models\Module as ModuleModel;
use App\Models\Submodule as SubmoduleModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SubmoduleRepository implements SubmoduleContract
{
    private $submoduleModel;

    public function __construct()
    {
        $this->submoduleModel = new SubmoduleModel;
    }

    public function all(string|null $search = null, int|null $perPage, SubmoduleAllRequest $request, ModuleId $moduleId): LengthAwarePaginator
    {
        try {
            $submodules = $this->submoduleModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['model', 'role', 'permission', 'submodules.submodules'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('types'), fn ($q) => $q->whereIn('type', $request->input('types')))
                ->where(function ($query) use ($moduleId, $request) {
                    $query->whereHasMorph('model', [ModuleModel::class], function ($q) use ($moduleId) {
                        $q->where('id', $moduleId->value());
                    })
                    ->orWhereHasMorph('model', [SubmoduleModel::class], function ($q) use ($moduleId) {
                        $q->whereHasMorph('model', [ModuleModel::class], function ($subQuery) use ($moduleId) {
                            $subQuery->where('id', $moduleId->value());
                        })
                        ->orWhereHasMorph('model', [SubmoduleModel::class], function ($q) use ($moduleId) {
                            $q->whereHasMorph('model', [ModuleModel::class], function ($subQuery) use ($moduleId) {
                                $subQuery->where('id', $moduleId->value());
                            });
                        });
                    });
                })
                ->orderBy('id', 'asc');

            return $submodules->paginate($perPage ?? $submodules->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los submodulos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los submodulos: ' . $e->getMessage());
        }
    }

    public function find(SubmoduleId $submoduleId): ?Submodule
    {
        try {
            $model = $this->submoduleModel
                ->with(['model', 'role', 'permission'])
                ->findOrFail($submoduleId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El submodulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el submodulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el submodulo: ' . $e->getMessage());
        }
    }

    public function store(Submodule $submodule): Submodule
    {
        try {
            $model = $this->submoduleModel
                ->create($this->mapToModelData($submodule));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el submodulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el submodulo: ' . $e->getMessage());
        }
    }

    public function update(Submodule $submodule, SubmoduleId $submoduleId): Submodule
    {
        try {
            $model = tap($this->submoduleModel->findOrFail($submoduleId->value()))
                ->update($this->mapToModelData($submodule))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El submodulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el submodulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el submodulo: ' . $e->getMessage());
        }
    }

    public function delete(SubmoduleId $submoduleId): Submodule
    {
        try {
            $model = tap($this->submoduleModel->findOrFail($submoduleId->value()), fn($submodule) => $submodule->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El submodulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el submodulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el submodulo: ' . $e->getMessage());
        }
    }

    public function restore(SubmoduleId $submoduleId): Submodule
    {
        try {
            $model = tap($this->submoduleModel->withTrashed()->findOrFail($submoduleId->value()), fn($submodule) => $submodule->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El submodulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el submodulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el submodulo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Submodule
    {
        return new Submodule(
            new SubmoduleId($model->id),
            new SubmoduleModelId($model->model_id),
            new SubmoduleModelType($model->model_type),
            new SubmoduleRoleId($model->role_id),
            new SubmodulePermissionId($model->permission_id),
            new SubmoduleType($model->type),
            new SubmoduleName($model->name),
            new SubmoduleRoute($model->route),
            new SubmoduleIcon($model->icon),
            new SubmoduleCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SubmoduleUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SubmoduleDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new SubmoduleRelationships($model->model, $model->role, $model->permission)
        );
    }

    private function mapToModelData(Submodule $submodule): array
    {
        $array = [];

        if ($submodule->modelId()->value()) {
            $array['model_id'] = $submodule->modelId()->value();
        }

        if ($submodule->modelType()->value()) {
            $array['model_type'] = $submodule->modelType()->value();
        }

        if ($submodule->roleId()->value()) {
            $array['role_id'] = $submodule->roleId()->value();
        }

        if ($submodule->permissionId()->value()) {
            $array['permission_id'] = $submodule->permissionId()->value();
        }

        if ($submodule->name()->value()) {
            $array['name'] = $submodule->name()->value();
        }

        if ($submodule->type()->value()) {
            $array['type'] = $submodule->type()->value();
        }

        if ($submodule->route()->value()) {
            $array['route'] = $submodule->route()->value();
        }

        if ($submodule->icon()->value()) {
            $array['icon'] = $submodule->icon()->value();
        }

        return $array;
    }
}
