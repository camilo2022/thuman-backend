<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\PensionFund\Contracts\PensionFundContract;
use App\Models\PensionFund as PensionFundModel;
use App\Domain\PensionFund\PensionFund;
use App\Domain\PensionFund\Values\PensionFundCountryId;
use App\Domain\PensionFund\Values\PensionFundCreatedAt;
use App\Domain\PensionFund\Values\PensionFundDeletedAt;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Domain\PensionFund\Values\PensionFundName;
use App\Domain\PensionFund\Values\PensionFundDescription;
use App\Domain\PensionFund\Values\PensionFundItemId;
use App\Domain\PensionFund\Values\PensionFundRelationships;
use App\Domain\PensionFund\Values\PensionFundSettings;
use App\Domain\PensionFund\Values\PensionFundUpdatedAt;
use App\Http\Requests\PensionFund\PensionFundAllRequest;
use App\Models\PensionFundAffiliation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PensionFundRepository implements PensionFundContract
{
    private $pensionFundModel;

    public function __construct()
    {
        $this->pensionFundModel = new PensionFundModel;
    }

    public function all(string|null $search = null, int|null $perPage, PensionFundAllRequest $request): LengthAwarePaginator
    {
        try {
            $pensionFunds = $this->pensionFundModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'countries'])
                ->where('item_id', 28)
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereHas('countries', fn($r) => $r->where('id', $request->integer('country_id')));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $pensionFunds->paginate($perPage ?? $pensionFunds->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los fondos de pensiones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los fondos de pensiones: ' . $e->getMessage());
        }
    }

    public function find(PensionFundId $pensionFundId): ?PensionFund
    {
        try {
            $model = $this->pensionFundModel->withTrashed()
                ->with(['item', 'countries'])->where('item_id', 28)
                ->findOrFail($pensionFundId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El fondo de pensión no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el fondo de pensión: ' . $e->getMessage());
        }
    }

    public function store(PensionFund $pensionFund): PensionFund
    {
        try {
            $model = $this->pensionFundModel
                ->create($this->mapToModelData($pensionFund));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el fondo de pensión: ' . $e->getMessage());
        }
    }

    public function update(PensionFund $pensionFund, PensionFundId $pensionFundId): PensionFund
    {
        try {
            $model = tap($this->pensionFundModel->where('item_id', 28)->whereKey($pensionFundId->value())->firstOrFail())
                ->update($this->mapToModelData($pensionFund))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El fondo de pensión no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el fondo de pensión: ' . $e->getMessage());
        }
    }

    public function toggle(PensionFund $pensionFund, PensionFundId $pensionFundId): PensionFund
    {
        try {
            $model = $this->pensionFundModel->where('item_id', 28)->whereKey($pensionFundId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $pensionFund->countryId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El fondo de pensión no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el fondo de pensión: ' . $e->getMessage());
        }
    }

    public function delete(PensionFundId $pensionFundId): PensionFund
    {
        try {
            $model = $this->pensionFundModel->where('item_id', 28)->whereKey($pensionFundId->value())->firstOrFail();

            $isUsed = PensionFundAffiliation::where('pension_fund_id', $pensionFundId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El fondo de pensión no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el fondo de pensión: ' . $e->getMessage());
        }
    }

    public function restore(PensionFundId $pensionFundId): PensionFund
    {
        try {
            $model = tap($this->pensionFundModel->withTrashed()->where('item_id', 28)->whereKey($pensionFundId->value())->firstOrFail(), fn($pensionFund) => $pensionFund->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El fondo de pensión no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el fondo de pensión: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): PensionFund
    {
        return new PensionFund(
            new PensionFundId($model->id),
            new PensionFundItemId($model->item_id),
            new PensionFundName($model->name),
            new PensionFundDescription($model->description),
            new PensionFundSettings($model->settings),
            new PensionFundCountryId(),
            new PensionFundCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PensionFundUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PensionFundDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new PensionFundRelationships($model->countries)
        );
    }

    private function mapToModelData(PensionFund $pensionFund): array
    {
        $array = [];

        if ($pensionFund->itemId()->value()) {
            $array['item_id'] = $pensionFund->itemId()->value();
        }

        if ($pensionFund->name()->value()) {
            $array['name'] = $pensionFund->name()->value();
        }

        if ($pensionFund->description()->value()) {
            $array['description'] = $pensionFund->description()->value();
        }

        if ($pensionFund->settings()->value()) {
            $array['settings'] = $pensionFund->settings()->value();
        }

        return $array;
    }
}
