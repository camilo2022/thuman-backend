<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\AbsenceType\Contracts\AbsenceTypeContract;
use App\Models\AbsenceType as AbsenceTypeModel;
use App\Domain\AbsenceType\AbsenceType;
use App\Domain\AbsenceType\Values\AbsenceTypeCreatedAt;
use App\Domain\AbsenceType\Values\AbsenceTypeDeletedAt;
use App\Domain\AbsenceType\Values\AbsenceTypeId;
use App\Domain\AbsenceType\Values\AbsenceTypeName;
use App\Domain\AbsenceType\Values\AbsenceTypeDescription;
use App\Domain\AbsenceType\Values\AbsenceTypeItemId;
use App\Domain\AbsenceType\Values\AbsenceTypeRelationships;
use App\Domain\AbsenceType\Values\AbsenceTypeSettings;
use App\Domain\AbsenceType\Values\AbsenceTypeUpdatedAt;
use App\Http\Requests\AbsenceType\AbsenceTypeAllRequest;
use App\Models\Absence;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AbsenceTypeRepository implements AbsenceTypeContract
{
    private $absenceTypeModel;

    public function __construct()
    {
        $this->absenceTypeModel = new AbsenceTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, AbsenceTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $absenceTypes = $this->absenceTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 40)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $absenceTypes->paginate($perPage ?? $absenceTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de permisos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de permisos: ' . $e->getMessage());
        }
    }

    public function find(AbsenceTypeId $absenceTypeId): ?AbsenceType
    {
        try {
            $model = $this->absenceTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 40)
                ->whereKey($absenceTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de permiso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de permiso: ' . $e->getMessage());
        }
    }

    public function store(AbsenceType $absenceType): AbsenceType
    {
        try {
            $model = $this->absenceTypeModel
                ->create($this->mapToModelData($absenceType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de permiso: ' . $e->getMessage());
        }
    }

    public function update(AbsenceType $absenceType, AbsenceTypeId $absenceTypeId): AbsenceType
    {
        try {
            $model = tap($this->absenceTypeModel->where('item_id', 40)->whereKey($absenceTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($absenceType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de permiso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de permiso: ' . $e->getMessage());
        }
    }

    public function delete(AbsenceTypeId $absenceTypeId): AbsenceType
    {
        try {
            $model = $this->absenceTypeModel->where('item_id', 40)->whereKey($absenceTypeId->value())->firstOrFail();

            $isUsed = Absence::where('absence_type_id', $absenceTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de permiso no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de permiso: ' . $e->getMessage());
        }
    }

    public function restore(AbsenceTypeId $absenceTypeId): AbsenceType
    {
        try {
            $model = tap($this->absenceTypeModel->withTrashed()->where('item_id', 40)->whereKey($absenceTypeId->value())->firstOrFail(), fn($absenceType) => $absenceType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de permiso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de permiso: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): AbsenceType
    {
        return new AbsenceType(
            new AbsenceTypeId($model->id),
            new AbsenceTypeItemId($model->item?->id ?? $model->item_id),
            new AbsenceTypeName($model->name),
            new AbsenceTypeDescription($model->description),
            new AbsenceTypeSettings($model->settings),
            new AbsenceTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AbsenceTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AbsenceTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new AbsenceTypeRelationships($model->item)
        );
    }

    private function mapToModelData(AbsenceType $absenceType): array
    {
        $array = [];

        if ($absenceType->itemId()->value()) {
            $array['item_id'] = $absenceType->itemId()->value();
        }

        if ($absenceType->name()->value()) {
            $array['name'] = $absenceType->name()->value();
        }

        if ($absenceType->description()->value()) {
            $array['description'] = $absenceType->description()->value();
        }

        if ($absenceType->settings()->compensationEnabled() !== null) {
            $array['settings']['compensation_enabled'] = $absenceType->settings()->compensationEnabled();
        }

        if ($absenceType->settings()->fileRequired() !== null) {
            $array['settings']['file_required'] = $absenceType->settings()->fileRequired();
        }

        if ($absenceType->settings()->message()) {
            $array['settings']['message'] = $absenceType->settings()->message();
        }

        if ($absenceType->settings()->validations()) {
            $array['settings']['validations'] = $absenceType->settings()->validations(false);
        }

        return $array;
    }
}
