<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\ContractType\Contracts\ContractTypeContract;
use App\Models\ContractType as ContractTypeModel;
use App\Domain\ContractType\ContractType;
use App\Domain\ContractType\Values\ContractTypeCreatedAt;
use App\Domain\ContractType\Values\ContractTypeDeletedAt;
use App\Domain\ContractType\Values\ContractTypeId;
use App\Domain\ContractType\Values\ContractTypeName;
use App\Domain\ContractType\Values\ContractTypeDescription;
use App\Domain\ContractType\Values\ContractTypeFileSubtypesId;
use App\Domain\ContractType\Values\ContractTypeItemId;
use App\Domain\ContractType\Values\ContractTypeRelationships;
use App\Domain\ContractType\Values\ContractTypeSettings;
use App\Domain\ContractType\Values\ContractTypeUpdatedAt;
use App\Http\Requests\ContractType\ContractTypeAllRequest;
use App\Models\Addendum;
use App\Models\Contract;
use App\Models\Vacant;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ContractTypeRepository implements ContractTypeContract
{
    private $contractTypeModel;

    public function __construct()
    {
        $this->contractTypeModel = new ContractTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, ContractTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $contractTypes = $this->contractTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'file_subtypes'])
                ->where('item_id', 1)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $contractTypes->paginate($perPage ?? $contractTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de contratos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de contratos: ' . $e->getMessage());
        }
    }

    public function find(ContractTypeId $contractTypeId): ?ContractType
    {
        try {
            $model = $this->contractTypeModel->withTrashed()
                ->with(['item', 'file_subtypes'])->where('item_id', 1)
                ->whereKey($contractTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de contrato: ' . $e->getMessage());
        }
    }

    public function store(ContractType $contractType): ContractType
    {
        try {
            $model = $this->contractTypeModel
                ->create($this->mapToModelData($contractType));

            $model->file_subtypes()->sync(array_filter(
                $contractType->fileSubtypesId()->value()
            ));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de contrato: ' . $e->getMessage());
        }
    }

    public function update(ContractType $contractType, ContractTypeId $contractTypeId): ContractType
    {
        try {
            $model = tap($this->contractTypeModel->where('item_id', 1)->whereKey($contractTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($contractType))
                ->refresh();

            $model->file_subtypes()->sync(array_filter(
                $contractType->fileSubtypesId()->value()
            ));

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de contrato: ' . $e->getMessage());
        }
    }

    public function delete(ContractTypeId $contractTypeId): ContractType
    {
        try {

            $model = $this->contractTypeModel->where('item_id', 1)->whereKey($contractTypeId->value())->firstOrFail();

            $isUserd = Vacant::where('contract_type_id', $contractTypeId->value())->exists() || Contract::where('contract_type_id', $contractTypeId->value())->exists()
                || Addendum::where('contract_type_id', $contractTypeId->value())->exists();

            if ($isUserd) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de contrato no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de contrato: ' . $e->getMessage());
        }
    }

    public function restore(ContractTypeId $contractTypeId): ContractType
    {
        try {
            $model = tap($this->contractTypeModel->withTrashed()->where('item_id', 1)->whereKey($contractTypeId->value())->firstOrFail(), fn($contractType) => $contractType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de contrato: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): ContractType
    {
        return new ContractType(
            new ContractTypeId($model->id),
            new ContractTypeItemId($model->item_id),
            new ContractTypeName($model->name),
            new ContractTypeDescription($model->description),
            new ContractTypeSettings($model->settings),
            new ContractTypeFileSubtypesId($model->file_subtypes->modelKeys()),
            new ContractTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ContractTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ContractTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new ContractTypeRelationships($model->file_subtypes_id)
        );
    }

    private function mapToModelData(ContractType $contractType): array
    {
        $array = [];

        if ($contractType->itemId()->value()) {
            $array['item_id'] = $contractType->itemId()->value();
        }

        if ($contractType->name()->value()) {
            $array['name'] = $contractType->name()->value();
        }

        if ($contractType->description()->value()) {
            $array['description'] = $contractType->description()->value();
        }

        if ($contractType->settings()->value()) {
            $array['settings'] = $contractType->settings()->value();
        }

        return $array;
    }
}
