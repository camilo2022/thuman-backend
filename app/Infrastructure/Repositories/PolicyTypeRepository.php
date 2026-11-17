<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\PolicyType\Contracts\PolicyTypeContract;
use App\Models\PolicyType as PolicyTypeModel;
use App\Domain\PolicyType\PolicyType;
use App\Domain\PolicyType\Values\PolicyTypeCreatedAt;
use App\Domain\PolicyType\Values\PolicyTypeDeletedAt;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Domain\PolicyType\Values\PolicyTypeName;
use App\Domain\PolicyType\Values\PolicyTypeDescription;
use App\Domain\PolicyType\Values\PolicyTypeItemId;
use App\Domain\PolicyType\Values\PolicyTypeSettings;
use App\Domain\PolicyType\Values\PolicyTypeUpdatedAt;
use App\Http\Requests\PolicyType\PolicyTypeAllRequest;
use App\Models\Policy;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PolicyTypeRepository implements PolicyTypeContract
{
    private $policyTypeModel;

    public function __construct()
    {
        $this->policyTypeModel = new PolicyTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, PolicyTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $policyTypes = $this->policyTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item',])
                ->where('item_id', 37)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $policyTypes->paginate($perPage ?? $policyTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de polizas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de polizas: ' . $e->getMessage());
        }
    }

    public function find(PolicyTypeId $policyTypeId): ?PolicyType
    {
        try {
            $model = $this->policyTypeModel->withTrashed()
                ->with(['item',])->where('item_id', 37)
                ->whereKey($policyTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de poliza no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de poliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de poliza: ' . $e->getMessage());
        }
    }

    public function store(PolicyType $policyType): PolicyType
    {
        try {
            $model = $this->policyTypeModel
                ->create($this->mapToModelData($policyType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de poliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de poliza: ' . $e->getMessage());
        }
    }

    public function update(PolicyType $policyType, PolicyTypeId $policyTypeId): PolicyType
    {
        try {
            $model = tap($this->policyTypeModel->where('item_id', 37)->whereKey($policyTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($policyType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de poliza no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de poliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de poliza: ' . $e->getMessage());
        }
    }

    public function delete(PolicyTypeId $policyTypeId): PolicyType
    {
        try {
            $model = $this->policyTypeModel->where('item_id', 37)->whereKey($policyTypeId->value())->firstOrFail();

            $isUsed = Policy::where('policy_type_id', $policyTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de poliza no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de poliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de poliza: ' . $e->getMessage());
        }
    }

    public function restore(PolicyTypeId $policyTypeId): PolicyType
    {
        try {
            $model = tap($this->policyTypeModel->withTrashed()->where('item_id', 37)->whereKey($policyTypeId->value())->firstOrFail(), fn($policyType) => $policyType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de poliza no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de poliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de poliza: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): PolicyType
    {
        return new PolicyType(
            new PolicyTypeId($model->id),
            new PolicyTypeItemId($model->item?->id ?? $model->item_id),
            new PolicyTypeName($model->name),
            new PolicyTypeDescription($model->description),
            new PolicyTypeSettings($model->settings),
            new PolicyTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PolicyTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PolicyTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(PolicyType $policyType): array
    {
        $array = [];

        if ($policyType->itemId()->value()) {
            $array['item_id'] = $policyType->itemId()->value();
        }

        if ($policyType->name()->value()) {
            $array['name'] = $policyType->name()->value();
        }

        if ($policyType->description()->value()) {
            $array['description'] = $policyType->description()->value();
        }

        return $array;
    }
}
