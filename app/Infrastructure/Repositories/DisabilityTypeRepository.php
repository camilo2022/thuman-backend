<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\DisabilityType\Contracts\DisabilityTypeContract;
use App\Models\DisabilityType as DisabilityTypeModel;
use App\Domain\DisabilityType\DisabilityType;
use App\Domain\DisabilityType\Values\DisabilityTypeCreatedAt;
use App\Domain\DisabilityType\Values\DisabilityTypeDeletedAt;
use App\Domain\DisabilityType\Values\DisabilityTypeId;
use App\Domain\DisabilityType\Values\DisabilityTypeName;
use App\Domain\DisabilityType\Values\DisabilityTypeDescription;
use App\Domain\DisabilityType\Values\DisabilityTypeItemId;
use App\Domain\DisabilityType\Values\DisabilityTypeRelationships;
use App\Domain\DisabilityType\Values\DisabilityTypeSettings;
use App\Domain\DisabilityType\Values\DisabilityTypeUpdatedAt;
use App\Http\Requests\DisabilityType\DisabilityTypeAllRequest;
use App\Models\Disability;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class DisabilityTypeRepository implements DisabilityTypeContract
{
    private $disabilityTypeModel;

    public function __construct()
    {
        $this->disabilityTypeModel = new DisabilityTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, DisabilityTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $disabilityTypes = $this->disabilityTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 43)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $disabilityTypes->paginate($perPage ?? $disabilityTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de incapacidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de incapacidades: ' . $e->getMessage());
        }
    }

    public function find(DisabilityTypeId $disabilityTypeId): ?DisabilityType
    {
        try {
            $model = $this->disabilityTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 43)
                ->whereKey($disabilityTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de incapacidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de incapacidad: ' . $e->getMessage());
        }
    }

    public function store(DisabilityType $disabilityType): DisabilityType
    {
        try {
            $model = $this->disabilityTypeModel
                ->create($this->mapToModelData($disabilityType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de incapacidad: ' . $e->getMessage());
        }
    }

    public function update(DisabilityType $disabilityType, DisabilityTypeId $disabilityTypeId): DisabilityType
    {
        try {
            $model = tap($this->disabilityTypeModel->where('item_id', 43)->whereKey($disabilityTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($disabilityType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de incapacidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de incapacidad: ' . $e->getMessage());
        }
    }

    public function delete(DisabilityTypeId $disabilityTypeId): DisabilityType
    {
        try {
            $model = $this->disabilityTypeModel->where('item_id', 43)->whereKey($disabilityTypeId->value())->firstOrFail();

            $isUsed = Disability::where('disability_type_id', $disabilityTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de incapacidad no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de incapacidad: ' . $e->getMessage());
        }
    }

    public function restore(DisabilityTypeId $disabilityTypeId): DisabilityType
    {
        try {
            $model = tap($this->disabilityTypeModel->withTrashed()->where('item_id', 43)->whereKey($disabilityTypeId->value())->firstOrFail(), fn($disabilityType) => $disabilityType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de incapacidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de incapacidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): DisabilityType
    {
        return new DisabilityType(
            new DisabilityTypeId($model->id),
            new DisabilityTypeItemId($model->item?->id ?? $model->item_id),
            new DisabilityTypeName($model->name),
            new DisabilityTypeDescription($model->description),
            new DisabilityTypeSettings($model->settings),
            new DisabilityTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new DisabilityTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new DisabilityTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new DisabilityTypeRelationships($model->item)
        );
    }

    private function mapToModelData(DisabilityType $disabilityType): array
    {
        $array = [];

        if ($disabilityType->itemId()->value()) {
            $array['item_id'] = $disabilityType->itemId()->value();
        }

        if ($disabilityType->name()->value()) {
            $array['name'] = $disabilityType->name()->value();
        }

        if ($disabilityType->description()->value()) {
            $array['description'] = $disabilityType->description()->value();
        }

        if ($disabilityType->settings()->fileRequired() !== null) {
            $array['settings']['file_required'] = $disabilityType->settings()->fileRequired();
        }

        if ($disabilityType->settings()->message()) {
            $array['settings']['message'] = $disabilityType->settings()->message();
        }

        if ($disabilityType->settings()->validations()) {
            $array['settings']['validations'] = $disabilityType->settings()->validations(false);
        }

        return $array;
    }
}
