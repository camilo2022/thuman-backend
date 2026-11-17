<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\TerminationType\Contracts\TerminationTypeContract;
use App\Models\TerminationType as TerminationTypeModel;
use App\Domain\TerminationType\TerminationType;
use App\Domain\TerminationType\Values\TerminationTypeCreatedAt;
use App\Domain\TerminationType\Values\TerminationTypeDeletedAt;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Domain\TerminationType\Values\TerminationTypeName;
use App\Domain\TerminationType\Values\TerminationTypeDescription;
use App\Domain\TerminationType\Values\TerminationTypeItemId;
use App\Domain\TerminationType\Values\TerminationTypeRelationships;
use App\Domain\TerminationType\Values\TerminationTypeSettings;
use App\Domain\TerminationType\Values\TerminationTypeUpdatedAt;
use App\Http\Requests\TerminationType\TerminationTypeAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class TerminationTypeRepository implements TerminationTypeContract
{
    private $terminationTypeModel;

    public function __construct()
    {
        $this->terminationTypeModel = new TerminationTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, TerminationTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $terminationTypes = $this->terminationTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 47)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $terminationTypes->paginate($perPage ?? $terminationTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de terminación de contratos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de terminación de contratos: ' . $e->getMessage());
        }
    }

    public function find(TerminationTypeId $terminationTypeId): ?TerminationType
    {
        try {
            $model = $this->terminationTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 47)
                ->whereKey($terminationTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function store(TerminationType $terminationType): TerminationType
    {
        try {
            $model = $this->terminationTypeModel
                ->create($this->mapToModelData($terminationType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function update(TerminationType $terminationType, TerminationTypeId $terminationTypeId): TerminationType
    {
        try {
            $model = tap($this->terminationTypeModel->where('item_id', 47)->whereKey($terminationTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($terminationType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function delete(TerminationTypeId $terminationTypeId): TerminationType
    {
        try {
            $model = tap($this->terminationTypeModel->where('item_id', 47)->whereKey($terminationTypeId->value())->firstOrFail(), fn($terminationType) => $terminationType->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function restore(TerminationTypeId $terminationTypeId): TerminationType
    {
        try {
            $model = tap($this->terminationTypeModel->withTrashed()->where('item_id', 47)->whereKey($terminationTypeId->value())->firstOrFail(), fn($terminationType) => $terminationType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de terminación de contrato: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): TerminationType
    {
        return new TerminationType(
            new TerminationTypeId($model->id),
            new TerminationTypeItemId($model->item?->id ?? $model->item_id),
            new TerminationTypeName($model->name),
            new TerminationTypeDescription($model->description),
            new TerminationTypeSettings($model->settings),
            new TerminationTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new TerminationTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new TerminationTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new TerminationTypeRelationships($model->item)
        );
    }

    private function mapToModelData(TerminationType $terminationType): array
    {
        $array = [];

        if ($terminationType->itemId()->value()) {
            $array['item_id'] = $terminationType->itemId()->value();
        }

        if ($terminationType->name()->value()) {
            $array['name'] = $terminationType->name()->value();
        }

        if ($terminationType->description()->value()) {
            $array['description'] = $terminationType->description()->value();
        }

        if ($terminationType->settings()->reasonRequired() !== null) {
            $array['settings']['reason_required'] = $terminationType->settings()->reasonRequired();
        }

        if ($terminationType->settings()->fileRequired() !== null) {
            $array['settings']['file_required'] = $terminationType->settings()->fileRequired();
        }

        if ($terminationType->settings()->massiveAllowed() !== null) {
            $array['settings']['massive_allowed'] = $terminationType->settings()->massiveAllowed();
        }

        if ($terminationType->settings()->message()) {
            $array['settings']['message'] = $terminationType->settings()->message();
        }

        if ($terminationType->settings()->validations()) {
            $array['settings']['validations'] = $terminationType->settings()->validations(false);
        }

        return $array;
    }
}
