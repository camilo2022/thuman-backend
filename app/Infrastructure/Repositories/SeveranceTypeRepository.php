<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\SeveranceType\Contracts\SeveranceTypeContract;
use App\Models\SeveranceType as SeveranceTypeModel;
use App\Domain\SeveranceType\SeveranceType;
use App\Domain\SeveranceType\Values\SeveranceTypeCreatedAt;
use App\Domain\SeveranceType\Values\SeveranceTypeDeletedAt;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Domain\SeveranceType\Values\SeveranceTypeName;
use App\Domain\SeveranceType\Values\SeveranceTypeDescription;
use App\Domain\SeveranceType\Values\SeveranceTypeItemId;
use App\Domain\SeveranceType\Values\SeveranceTypeRelationships;
use App\Domain\SeveranceType\Values\SeveranceTypeSettings;
use App\Domain\SeveranceType\Values\SeveranceTypeUpdatedAt;
use App\Http\Requests\SeveranceType\SeveranceTypeAllRequest;
use App\Models\Severance;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SeveranceTypeRepository implements SeveranceTypeContract
{
    private $severanceTypeModel;

    public function __construct()
    {
        $this->severanceTypeModel = new SeveranceTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, SeveranceTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $severanceTypes = $this->severanceTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 42)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $severanceTypes->paginate($perPage ?? $severanceTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de cesantías: ' . $e->getMessage());
        }
    }

    public function find(SeveranceTypeId $severanceTypeId): ?SeveranceType
    {
        try {
            $model = $this->severanceTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 42)
                ->whereKey($severanceTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de cesantías no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de cesantías: ' . $e->getMessage());
        }
    }

    public function store(SeveranceType $severanceType): SeveranceType
    {
        try {
            $model = $this->severanceTypeModel
                ->create($this->mapToModelData($severanceType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de cesantías: ' . $e->getMessage());
        }
    }

    public function update(SeveranceType $severanceType, SeveranceTypeId $severanceTypeId): SeveranceType
    {
        try {
            $model = tap($this->severanceTypeModel->where('item_id', 42)->whereKey($severanceTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($severanceType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de cesantías no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de cesantías: ' . $e->getMessage());
        }
    }

    public function delete(SeveranceTypeId $severanceTypeId): SeveranceType
    {
        try {
            $model = $this->severanceTypeModel->where('item_id', 42)->whereKey($severanceTypeId->value())->firstOrFail();

            $isUsed = Severance::where('severance_type_id', $severanceTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de cesantías no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de cesantías: ' . $e->getMessage());
        }
    }

    public function restore(SeveranceTypeId $severanceTypeId): SeveranceType
    {
        try {
            $model = tap($this->severanceTypeModel->withTrashed()->where('item_id', 42)->whereKey($severanceTypeId->value())->firstOrFail(), fn($severanceType) => $severanceType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de cesantías no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de cesantías: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): SeveranceType
    {
        return new SeveranceType(
            new SeveranceTypeId($model->id),
            new SeveranceTypeItemId($model->item?->id ?? $model->item_id),
            new SeveranceTypeName($model->name),
            new SeveranceTypeDescription($model->description),
            new SeveranceTypeSettings($model->settings),
            new SeveranceTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SeveranceTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SeveranceTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new SeveranceTypeRelationships($model->item)
        );
    }

    private function mapToModelData(SeveranceType $severanceType): array
    {
        $array = [];

        if ($severanceType->itemId()->value()) {
            $array['item_id'] = $severanceType->itemId()->value();
        }

        if ($severanceType->name()->value()) {
            $array['name'] = $severanceType->name()->value();
        }

        // if ($severanceType->description()->value()) {
        //     $array['description'] = $severanceType->description()->value();
        // }
        $array['description'] = $severanceType->description()->value();

        if ($severanceType->settings()->fileRequired() !== null) {
            $array['settings']['file_required'] = $severanceType->settings()->fileRequired();
        }

        if ($severanceType->settings()->message()) {
            $array['settings']['message'] = $severanceType->settings()->message();
        }

        if ($severanceType->settings()->validations()) {
            $array['settings']['validations'] = $severanceType->settings()->validations(false);
        }

        return $array;
    }
}
