<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Area\Contracts\AreaContract;
use App\Models\Area as AreaModel;
use App\Domain\Area\Area;
use App\Domain\Area\Values\AreaCreatedAt;
use App\Domain\Area\Values\AreaDeletedAt;
use App\Domain\Area\Values\AreaId;
use App\Domain\Area\Values\AreaName;
use App\Domain\Area\Values\AreaDescription;
use App\Domain\Area\Values\AreaUpdatedAt;
use App\Http\Requests\Area\AreaAllRequest;
use App\Models\Position;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AreaRepository implements AreaContract
{
    private $areaModel;

    public function __construct()
    {
        $this->areaModel = new AreaModel;
    }

    public function all(string|null $search = null, int|null $perPage, AreaAllRequest $request): LengthAwarePaginator
    {
        try {
            $areas = $this->areaModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $areas->paginate($perPage ?? $areas->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las areas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las areas: ' . $e->getMessage());
        }
    }

    public function find(AreaId $areaId): ?Area
    {
        try {
            $model = $this->areaModel->withTrashed()
                ->findOrFail($areaId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El área no existe:' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el área: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el área: ' . $e->getMessage());
        }
    }

    public function store(Area $area): Area
    {
        try {
            $model = $this->areaModel
                ->create($this->mapToModelData($area));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el área: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el área: ' . $e->getMessage());
        }
    }

    public function update(Area $area, AreaId $areaId): Area
    {
        try {
            $model = tap($this->areaModel->findOrFail($areaId->value()))
                ->update($this->mapToModelData($area))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El área no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el área: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el área: ' . $e->getMessage());
        }
    }

    public function delete(AreaId $areaId): Area
    {
        try {

            $model = $this->areaModel->findOrFail($areaId->value());

            $isUsed = Position::where('area_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El área no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el área: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el área: ' . $e->getMessage());
        }
    }

    public function restore(AreaId $areaId): Area
    {
        try {
            $model = tap($this->areaModel->withTrashed()->findOrFail($areaId->value()), fn($area) => $area->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El área no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el área: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el área: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Area
    {
        return new Area(
            new AreaId($model->id),
            new AreaName($model->name),
            new AreaDescription($model->description),
            new AreaCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AreaUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AreaDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Area $area): array
    {
        $array = [];

        if ($area->name()->value()) {
            $array['name'] = $area->name()->value();
        }

        if ($area->description()->value()) {
            $array['description'] = $area->description()->value();
        }

        return $array;
    }
}
