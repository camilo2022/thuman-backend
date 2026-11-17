<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\VacationType\Contracts\VacationTypeContract;
use App\Models\VacationType as VacationTypeModel;
use App\Domain\VacationType\VacationType;
use App\Domain\VacationType\Values\VacationTypeCreatedAt;
use App\Domain\VacationType\Values\VacationTypeDeletedAt;
use App\Domain\VacationType\Values\VacationTypeId;
use App\Domain\VacationType\Values\VacationTypeName;
use App\Domain\VacationType\Values\VacationTypeDescription;
use App\Domain\VacationType\Values\VacationTypeItemId;
use App\Domain\VacationType\Values\VacationTypeRelationships;
use App\Domain\VacationType\Values\VacationTypeSettings;
use App\Domain\VacationType\Values\VacationTypeUpdatedAt;
use App\Http\Requests\VacationType\VacationTypeAllRequest;
use App\Models\Vacation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class VacationTypeRepository implements VacationTypeContract
{
    private $vacationTypeModel;

    public function __construct()
    {
        $this->vacationTypeModel = new VacationTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, VacationTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $vacationTypes = $this->vacationTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 41)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $vacationTypes->paginate($perPage ?? $vacationTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de vacaciones: ' . $e->getMessage());
        }
    }

    public function find(VacationTypeId $vacationTypeId): ?VacationType
    {
        try {
            $model = $this->vacationTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 41)
                ->whereKey($vacationTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de vacaciones no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de vacaciones: ' . $e->getMessage());
        }
    }

    public function store(VacationType $vacationType): VacationType
    {
        try {
            $model = $this->vacationTypeModel
                ->create($this->mapToModelData($vacationType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de vacaciones: ' . $e->getMessage());
        }
    }

    public function update(VacationType $vacationType, VacationTypeId $vacationTypeId): VacationType
    {
        try {
            $model = tap($this->vacationTypeModel->where('item_id', 41)->whereKey($vacationTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($vacationType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de vacaciones no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de vacaciones: ' . $e->getMessage());
        }
    }

    public function delete(VacationTypeId $vacationTypeId): VacationType
    {
        try {
            $model = $this->vacationTypeModel->where('item_id', 41)->whereKey($vacationTypeId->value())->firstOrFail();

            $isUsed = Vacation::where('vacation_type_id', $vacationTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de vacaciones no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de vacaciones: ' . $e->getMessage());
        }
    }

    public function restore(VacationTypeId $vacationTypeId): VacationType
    {
        try {
            $model = tap($this->vacationTypeModel->withTrashed()->where('item_id', 41)->whereKey($vacationTypeId->value())->firstOrFail(), fn($vacationType) => $vacationType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de vacaciones no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de vacaciones: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): VacationType
    {
        return new VacationType(
            new VacationTypeId($model->id),
            new VacationTypeItemId($model->item?->id ?? $model->item_id),
            new VacationTypeName($model->name),
            new VacationTypeDescription($model->description),
            new VacationTypeSettings($model->settings),
            new VacationTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new VacationTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new VacationTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new VacationTypeRelationships($model->item)
        );
    }

    private function mapToModelData(VacationType $vacationType): array
    {
        $array = [];

        if ($vacationType->itemId()->value()) {
            $array['item_id'] = $vacationType->itemId()->value();
        }

        if ($vacationType->name()->value()) {
            $array['name'] = $vacationType->name()->value();
        }

        // if ($vacationType->description()->value()) {
        //     $array['description'] = $vacationType->description()->value();
        // }
        $array['description'] = $vacationType->description()->value();

        if ($vacationType->settings()->fieldType() !== null) {
            $array['settings']['field_type'] = $vacationType->settings()->fieldType();
        }

        if ($vacationType->settings()->message()) {
            $array['settings']['message'] = $vacationType->settings()->message();
        }

        if ($vacationType->settings()->validations()) {
            $array['settings']['validations'] = $vacationType->settings()->validations(false);
        }

        return $array;
    }
}
