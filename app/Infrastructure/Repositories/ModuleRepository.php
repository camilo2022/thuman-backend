<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Navigation\Module\Contracts\ModuleContract;
use App\Domain\Navigation\Module\Module;
use App\Domain\Navigation\Module\Values\ModuleCreatedAt;
use App\Domain\Navigation\Module\Values\ModuleDeletedAt;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Domain\Navigation\Module\Values\ModuleName;
use App\Domain\Navigation\Module\Values\ModuleRoute;
use App\Domain\Navigation\Module\Values\ModuleType;
use App\Domain\Navigation\Module\Values\ModuleUpdatedAt;
use App\Http\Requests\Navigation\Module\ModuleAllRequest;
use App\Models\Module as ModuleModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ModuleRepository implements ModuleContract
{
    private $moduleModel;

    public function __construct()
    {
        $this->moduleModel = new ModuleModel;
    }

    public function all(string|null $search = null, int|null $perPage, ModuleAllRequest $request): LengthAwarePaginator
    {
        try {
            $modules = $this->moduleModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['submodules.role'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('type'), function ($q) use ($request) {
                    $q->where('type', $request->input('type'));
                })
                ->orderBy('id', 'asc');

            return $modules->paginate($perPage ?? $modules->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los modulos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los modulos: ' . $e->getMessage());
        }
    }

    public function find(ModuleId $moduleId): ?Module
    {
        try {
            $model = $this->moduleModel->withTrashed()
                ->findOrFail($moduleId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El modulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el modulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el modulo: ' . $e->getMessage());
        }
    }

    public function store(Module $module): Module
    {
        try {
            $model = $this->moduleModel
                ->create($this->mapToModelData($module));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el modulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el modulo: ' . $e->getMessage());
        }
    }

    public function update(Module $module, ModuleId $moduleId): Module
    {
        try {
            $model = tap($this->moduleModel->findOrFail($moduleId->value()))
                ->update($this->mapToModelData($module))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El modulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el modulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el modulo: ' . $e->getMessage());
        }
    }

    public function delete(ModuleId $moduleId): Module
    {
        try {
            $model = tap($this->moduleModel->findOrFail($moduleId->value()), fn($module) => $module->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El modulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el modulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el modulo: ' . $e->getMessage());
        }
    }

    public function restore(ModuleId $moduleId): Module
    {
        try {
            $model = tap($this->moduleModel->withTrashed()->findOrFail($moduleId->value()), fn($module) => $module->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El modulo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el modulo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el modulo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Module
    {
        return new Module(
            new ModuleId($model->id),
            new ModuleName($model->name),
            new ModuleType($model->type),
            new ModuleRoute($model->route),
            new ModuleCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ModuleUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ModuleDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Module $module): array
    {
        $array = [];

        if ($module->name()->value()) {
            $array['name'] = $module->name()->value();
        }

        if ($module->type()->value()) {
            $array['type'] = $module->type()->value();
        }

        if ($module->route()->value()) {
            $array['route'] = $module->route()->value();
        }

        return $array;
    }
}
