<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\EntityType\Contracts\EntityTypeContract;
use App\Models\EntityType as EntityTypeModel;
use App\Domain\EntityType\EntityType;
use App\Domain\EntityType\Values\EntityTypeCountryId;
use App\Domain\EntityType\Values\EntityTypeCreatedAt;
use App\Domain\EntityType\Values\EntityTypeDeletedAt;
use App\Domain\EntityType\Values\EntityTypeId;
use App\Domain\EntityType\Values\EntityTypeName;
use App\Domain\EntityType\Values\EntityTypeDescription;
use App\Domain\EntityType\Values\EntityTypeItemId;
use App\Domain\EntityType\Values\EntityTypeRelationships;
use App\Domain\EntityType\Values\EntityTypeSettings;
use App\Domain\EntityType\Values\EntityTypeUpdatedAt;
use App\Http\Requests\EntityType\EntityTypeAllRequest;
use App\Models\Entity;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EntityTypeRepository implements EntityTypeContract
{
    private $entityTypeModel;

    public function __construct()
    {
        $this->entityTypeModel = new EntityTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, EntityTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $entityTypes = $this->entityTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 30)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $entityTypes->paginate($perPage ?? $entityTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de entidad: ' . $e->getMessage());
        }
    }

    public function find(EntityTypeId $entityTypeId): ?EntityType
    {
        try {
            $model = $this->entityTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 30)
                ->whereKey($entityTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de entidad: ' . $e->getMessage());
        }
    }

    public function store(EntityType $entityType): EntityType
    {
        try {
            $model = $this->entityTypeModel
                ->create($this->mapToModelData($entityType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de entidad: ' . $e->getMessage());
        }
    }

    public function update(EntityType $entityType, EntityTypeId $entityTypeId): EntityType
    {
        try {
            $model = tap($this->entityTypeModel->where('item_id', 30)->whereKey($entityTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($entityType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de entidad: ' . $e->getMessage());
        }
    }

    public function toggle(EntityType $entityType, EntityTypeId $entityTypeId): EntityType
    {
        try {
            $model = $this->entityTypeModel->where('item_id', 30)->whereKey($entityTypeId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $entityType->countryId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de entidad: ' . $e->getMessage());
        }
    }

    public function delete(EntityTypeId $entityTypeId): EntityType
    {
        try {
            $model = $this->entityTypeModel
                ->where('item_id', 30)
                ->whereKey($entityTypeId->value())
                ->firstOrFail();

            $isUsed = Entity::where('entity_type_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de entidad no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al inactivar el tipo de entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al inactivar el tipo de entidad: ' . $e->getMessage());
        }
    }

    public function restore(EntityTypeId $entityTypeId): EntityType
    {
        try {
            $model = tap($this->entityTypeModel->withTrashed()->where('item_id', 30)->whereKey($entityTypeId->value())->firstOrFail(), fn($entityType) => $entityType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de entidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): EntityType
    {
        return new EntityType(
            new EntityTypeId($model->id),
            new EntityTypeItemId($model->item?->id ?? $model->item_id),
            new EntityTypeName($model->name),
            new EntityTypeDescription($model->description),
            new EntityTypeSettings($model->settings),
            new EntityTypeCountryId(),
            new EntityTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new EntityTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new EntityTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new EntityTypeRelationships($model->countries)
        );
    }

    private function mapToModelData(EntityType $entityType): array
    {
        $array = [];

        if ($entityType->itemId()->value()) {
            $array['item_id'] = $entityType->itemId()->value();
        }

        if ($entityType->name()->value()) {
            $array['name'] = $entityType->name()->value();
        }

        if ($entityType->description()->value()) {
            $array['description'] = $entityType->description()->value();
        }

        if ($entityType->settings()->value()) {
            $array['settings'] = $entityType->settings()->value();
        }

        return $array;
    }
}
