<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\CompensationFund\Contracts\CompensationFundContract;
use App\Models\CompensationFund as CompensationFundModel;
use App\Domain\CompensationFund\CompensationFund;
use App\Domain\CompensationFund\Values\CompensationFundCountryId;
use App\Domain\CompensationFund\Values\CompensationFundCreatedAt;
use App\Domain\CompensationFund\Values\CompensationFundDeletedAt;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Domain\CompensationFund\Values\CompensationFundName;
use App\Domain\CompensationFund\Values\CompensationFundDescription;
use App\Domain\CompensationFund\Values\CompensationFundItemId;
use App\Domain\CompensationFund\Values\CompensationFundRelationships;
use App\Domain\CompensationFund\Values\CompensationFundSettings;
use App\Domain\CompensationFund\Values\CompensationFundUpdatedAt;
use App\Http\Requests\CompensationFund\CompensationFundAllRequest;
use App\Models\CompensationFundAffiliation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CompensationFundRepository implements CompensationFundContract
{
    private $compensationFundModel;

    public function __construct()
    {
        $this->compensationFundModel = new CompensationFundModel;
    }

    public function all(string|null $search = null, int|null $perPage, CompensationFundAllRequest $request): LengthAwarePaginator
    {
        try {
            $compensationFunds = $this->compensationFundModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'countries'])
                ->where('item_id', 27)
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereHas('countries', fn($r) => $r->where('id', $request->integer('country_id')));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $compensationFunds->paginate($perPage ?? $compensationFunds->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las cajas de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las cajas de compensación: ' . $e->getMessage());
        }
    }

    public function find(CompensationFundId $compensationFundId): ?CompensationFund
    {
        try {
            $model = $this->compensationFundModel->withTrashed()
                ->with(['item', 'countries'])->where('item_id', 27)
                ->findOrFail($compensationFundId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La caja de compensación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la caja de compensación: ' . $e->getMessage());
        }
    }

    public function store(CompensationFund $compensationFund): CompensationFund
    {
        try {
            $model = $this->compensationFundModel
                ->create($this->mapToModelData($compensationFund));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la caja de compensación: ' . $e->getMessage());
        }
    }

    public function update(CompensationFund $compensationFund, CompensationFundId $compensationFundId): CompensationFund
    {
        try {
            $model = tap($this->compensationFundModel->where('item_id', 27)->whereKey($compensationFundId->value())->firstOrFail())
                ->update($this->mapToModelData($compensationFund))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La caja de compensación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la caja de compensación: ' . $e->getMessage());
        }
    }

    public function toggle(CompensationFund $compensationFund, CompensationFundId $compensationFundId): CompensationFund
    {
        try {
            $model = $this->compensationFundModel->where('item_id', 27)->whereKey($compensationFundId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $compensationFund->countryId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La caja de compensación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la caja de compensación: ' . $e->getMessage());
        }
    }

    public function delete(CompensationFundId $compensationFundId): CompensationFund
    {
        try {
            $model = $this->compensationFundModel->where('item_id', 27)->whereKey($compensationFundId->value())->firstOrFail();

            $isUsed = CompensationFundAffiliation::where('compensation_fund_id', $compensationFundId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La caja de compensación no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la caja de compensación: ' . $e->getMessage());
        }
    }

    public function restore(CompensationFundId $compensationFundId): CompensationFund
    {
        try {
            $model = tap($this->compensationFundModel->withTrashed()->where('item_id', 27)->whereKey($compensationFundId->value())->firstOrFail(), fn($compensationFund) => $compensationFund->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La caja de compensación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la caja de compensación: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): CompensationFund
    {
        return new CompensationFund(
            new CompensationFundId($model->id),
            new CompensationFundItemId($model->item_id),
            new CompensationFundName($model->name),
            new CompensationFundDescription($model->description),
            new CompensationFundSettings($model->settings),
            new CompensationFundCountryId(),
            new CompensationFundCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CompensationFundUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CompensationFundDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new CompensationFundRelationships($model->countries)
        );
    }

    private function mapToModelData(CompensationFund $compensationFund): array
    {
        $array = [];

        if ($compensationFund->itemId()->value()) {
            $array['item_id'] = $compensationFund->itemId()->value();
        }

        if ($compensationFund->name()->value()) {
            $array['name'] = $compensationFund->name()->value();
        }

        if ($compensationFund->description()->value()) {
            $array['description'] = $compensationFund->description()->value();
        }

        if ($compensationFund->settings()->value()) {
            $array['settings'] = $compensationFund->settings()->value();
        }

        return $array;
    }
}
