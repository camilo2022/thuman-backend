<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\HealthEntity\Contracts\HealthEntityContract;
use App\Models\HealthEntity as HealthEntityModel;
use App\Domain\HealthEntity\HealthEntity;
use App\Domain\HealthEntity\Values\HealthEntityCountryId;
use App\Domain\HealthEntity\Values\HealthEntityCreatedAt;
use App\Domain\HealthEntity\Values\HealthEntityDeletedAt;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Domain\HealthEntity\Values\HealthEntityName;
use App\Domain\HealthEntity\Values\HealthEntityDescription;
use App\Domain\HealthEntity\Values\HealthEntityHealthEntityTypeId;
use App\Domain\HealthEntity\Values\HealthEntityItemId;
use App\Domain\HealthEntity\Values\HealthEntityRelationships;
use App\Domain\HealthEntity\Values\HealthEntitySettings;
use App\Domain\HealthEntity\Values\HealthEntityUpdatedAt;
use App\Http\Requests\HealthEntity\HealthEntityAllRequest;
use App\Models\ExamMedical;
use App\Models\HealthEntityAffiliation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class HealthEntityRepository implements HealthEntityContract
{
    private $healthEntityModel;

    public function __construct()
    {
        $this->healthEntityModel = new HealthEntityModel;
    }

    public function all(string|null $search = null, int|null $perPage, HealthEntityAllRequest $request): LengthAwarePaginator
    {
        try {
            $healthEntitys = $this->healthEntityModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'health_entity_type', 'countries'])
                ->where('item_id', 26)
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereHas('countries', fn($r) => $r->where('id', $request->integer('country_id')));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $healthEntitys->paginate($perPage ?? $healthEntitys->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las entidades de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las entidades de salud: ' . $e->getMessage());
        }
    }

    public function find(HealthEntityId $healthEntityId): ?HealthEntity
    {
        try {
            $model = $this->healthEntityModel->withTrashed()
                ->with(['item', 'health_entity_type', 'countries'])->where('item_id', 26)
                ->whereKey($healthEntityId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad de salud no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la entidad de salud: ' . $e->getMessage());
        }
    }

    public function store(HealthEntity $healthEntity): HealthEntity
    {
        try {
            $model = $this->healthEntityModel
                ->create($this->mapToModelData($healthEntity));

            $model->health_entity_type()->sync(array_filter([
                $healthEntity->healthEntityTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la entidad de salud: ' . $e->getMessage());
        }
    }

    public function update(HealthEntity $healthEntity, HealthEntityId $healthEntityId): HealthEntity
    {
        try {
            $model = tap($this->healthEntityModel->where('item_id', 26)->whereKey($healthEntityId->value())->firstOrFail())
                ->update($this->mapToModelData($healthEntity))
                ->refresh();

            $model->health_entity_type()->sync(array_filter([
                $healthEntity->healthEntityTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad de salud no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la entidad de salud: ' . $e->getMessage());
        }
    }

    public function toggle(HealthEntity $healthEntity, HealthEntityId $healthEntityId): HealthEntity
    {
        try {
            $model = $this->healthEntityModel->where('item_id', 26)->whereKey($healthEntityId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $healthEntity->countryId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad de salud no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la entidad de salud: ' . $e->getMessage());
        }
    }

    public function delete(HealthEntityId $healthEntityId): HealthEntity
    {
        try {
            $model = $this->healthEntityModel->where('item_id', 26)->whereKey($healthEntityId->value())->firstOrFail();

            $isUsed = ExamMedical::where('health_entity_id', $healthEntityId->value())->exists() || HealthEntityAffiliation::where('health_entity_id', $healthEntityId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad de salud no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la entidad de salud: ' . $e->getMessage());
        }
    }

    public function restore(HealthEntityId $healthEntityId): HealthEntity
    {
        try {
            $model = tap($this->healthEntityModel->withTrashed()->where('item_id', 26)->whereKey($healthEntityId->value())->firstOrFail(), fn($healthEntity) => $healthEntity->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad de salud no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la entidad de salud: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): HealthEntity
    {
        return new HealthEntity(
            new HealthEntityId($model->id),
            new HealthEntityHealthEntityTypeId($model->health_entity_type->first()?->id),
            new HealthEntityItemId($model->item?->id ?? $model->item_id),
            new HealthEntityName($model->name),
            new HealthEntityDescription($model->description),
            new HealthEntitySettings($model->settings),
            new HealthEntityCountryId(),
            new HealthEntityCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new HealthEntityUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new HealthEntityDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new HealthEntityRelationships($model->countries)
        );
    }

    private function mapToModelData(HealthEntity $healthEntity): array
    {
        $array = [];

        if ($healthEntity->itemId()->value()) {
            $array['item_id'] = $healthEntity->itemId()->value();
        }

        if ($healthEntity->name()->value()) {
            $array['name'] = $healthEntity->name()->value();
        }

        if ($healthEntity->description()->value()) {
            $array['description'] = $healthEntity->description()->value();
        }

        if ($healthEntity->settings()->value()) {
            $array['settings'] = $healthEntity->settings()->value();
        }

        return $array;
    }
}
