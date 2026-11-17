<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Enterprise\Entity\Headquarter\Contracts\HeadquarterContract;
use App\Models\Headquarter as HeadquarterModel;
use App\Domain\Enterprise\Entity\Headquarter\Headquarter;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterAddress;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterCityId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterCreatedAt;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterDeletedAt;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterEntityId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterName;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterRelationships;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterUpdatedAt;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Headquarter\HeadquarterAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class HeadquarterRepository implements HeadquarterContract
{
    private $headquarterModel;

    public function __construct()
    {
        $this->headquarterModel = new HeadquarterModel;
    }

    public function all(string|null $search = null, int|null $perPage, EntityId $entityId, HeadquarterAllRequest $request): LengthAwarePaginator
    {
        try {
            $headquarters = $this->headquarterModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['entity', 'city.departament.country'])
                ->where('entity_id', $entityId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $headquarters->paginate($perPage ?? $headquarters->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las sedes de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las sedes de la entidad: ' . $e->getMessage());
        }
    }

    public function find(HeadquarterId $headquarterId): ?Headquarter
    {
        try {
            $model = $this->headquarterModel->withTrashed()
                ->with(['entity', 'city.departament.country'])
                ->findOrFail($headquarterId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function store(Headquarter $headquarter): Headquarter
    {
        try {
            $model = $this->headquarterModel
                ->create($this->mapToModelData($headquarter));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function update(Headquarter $headquarter, HeadquarterId $headquarterId): Headquarter
    {
        try {
            $model = tap($this->headquarterModel->findOrFail($headquarterId->value()))
                ->update($this->mapToModelData($headquarter))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function delete(HeadquarterId $headquarterId): Headquarter
    {
        try {
            $model = tap($this->headquarterModel->findOrFail($headquarterId->value()), fn($headquarter) => $headquarter->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function restore(HeadquarterId $headquarterId): Headquarter
    {
        try {
            $model = tap($this->headquarterModel->withTrashed()->findOrFail($headquarterId->value()), fn($headquarter) => $headquarter->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la sede de la entidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Headquarter
    {
        return new Headquarter(
            new HeadquarterId($model->id),
            new HeadquarterEntityId($model->entity?->id ?? $model->entity_id),
            new HeadquarterName($model->name),
            new HeadquarterCityId($model->city?->id ?? $model->city_id),
            new HeadquarterAddress($model->address),
            new HeadquarterCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new HeadquarterUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new HeadquarterDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new HeadquarterRelationships($model->entity, $model->city)
        );
    }

    private function mapToModelData(Headquarter $headquarter): array
    {
        $array = [];

        if ($headquarter->entityId()->value()) {
            $array['entity_id'] = $headquarter->entityId()->value();
        }

        if ($headquarter->name()->value()) {
            $array['name'] = $headquarter->name()->value();
        }

        if ($headquarter->cityId()->value()) {
            $array['city_id'] = $headquarter->cityId()->value();
        }

        if ($headquarter->address()->value()) {
            $array['address'] = $headquarter->address()->value();
        }

        return $array;
    }
}
