<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\AddendumType\Contracts\AddendumTypeContract;
use App\Models\AddendumType as AddendumTypeModel;
use App\Domain\AddendumType\AddendumType;
use App\Domain\AddendumType\Values\AddendumTypeCreatedAt;
use App\Domain\AddendumType\Values\AddendumTypeDeletedAt;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Domain\AddendumType\Values\AddendumTypeName;
use App\Domain\AddendumType\Values\AddendumTypeDescription;
use App\Domain\AddendumType\Values\AddendumTypeItemId;
use App\Domain\AddendumType\Values\AddendumTypeRelationships;
use App\Domain\AddendumType\Values\AddendumTypeSettings;
use App\Domain\AddendumType\Values\AddendumTypeUpdatedAt;
use App\Http\Requests\AddendumType\AddendumTypeAllRequest;
use App\Models\Addendum;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AddendumTypeRepository implements AddendumTypeContract
{
    private $addendumTypeModel;

    public function __construct()
    {
        $this->addendumTypeModel = new AddendumTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, AddendumTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $addendumTypes = $this->addendumTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item',])
                ->where('item_id', 38)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $addendumTypes->paginate($perPage ?? $addendumTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de otrosíes: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de otrosíes: ' . $e->getMessage());
        }
    }

    public function find(AddendumTypeId $addendumTypeId): ?AddendumType
    {
        try {
            $model = $this->addendumTypeModel->withTrashed()
                ->with(['item',])->where('item_id', 38)
                ->whereKey($addendumTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de otrosí no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de otrosí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de otrosí: ' . $e->getMessage());
        }
    }

    public function store(AddendumType $addendumType): AddendumType
    {
        try {
            $model = $this->addendumTypeModel
                ->create($this->mapToModelData($addendumType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de otrosí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de otrosí: ' . $e->getMessage());
        }
    }

    public function update(AddendumType $addendumType, AddendumTypeId $addendumTypeId): AddendumType
    {
        try {
            $model = tap($this->addendumTypeModel->where('item_id', 38)->whereKey($addendumTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($addendumType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de otrosí no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de otrosí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de otrosí: ' . $e->getMessage());
        }
    }

    public function delete(AddendumTypeId $addendumTypeId): AddendumType
    {
        try {
            $model = $this->addendumTypeModel->where('item_id', 38)->whereKey($addendumTypeId->value())->firstOrFail();

            $isUsed = Addendum::where('addendum_type_id', $addendumTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de otrosí no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de otrosí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de otrosí: ' . $e->getMessage());
        }
    }

    public function restore(AddendumTypeId $addendumTypeId): AddendumType
    {
        try {
            $model = tap($this->addendumTypeModel->withTrashed()->where('item_id', 38)->whereKey($addendumTypeId->value())->firstOrFail(), fn($addendumType) => $addendumType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de otrosí no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de otrosí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de otrosí: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): AddendumType
    {
        return new AddendumType(
            new AddendumTypeId($model->id),
            new AddendumTypeItemId($model->item?->id ?? $model->item_id),
            new AddendumTypeName($model->name),
            new AddendumTypeDescription($model->description),
            new AddendumTypeSettings($model->settings),
            new AddendumTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AddendumTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AddendumTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new AddendumTypeRelationships($model->item)
        );
    }

    private function mapToModelData(AddendumType $addendumType): array
    {
        $array = [];

        if ($addendumType->itemId()->value()) {
            $array['item_id'] = $addendumType->itemId()->value();
        }

        if ($addendumType->name()->value()) {
            $array['name'] = $addendumType->name()->value();
        }

        if ($addendumType->description()->value()) {
            $array['description'] = $addendumType->description()->value();
        }

        return $array;
    }
}
