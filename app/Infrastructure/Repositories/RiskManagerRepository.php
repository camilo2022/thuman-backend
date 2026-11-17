<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\RiskManager\Contracts\RiskManagerContract;
use App\Models\RiskManager as RiskManagerModel;
use App\Domain\RiskManager\RiskManager;
use App\Domain\RiskManager\Values\RiskManagerCountryId;
use App\Domain\RiskManager\Values\RiskManagerCreatedAt;
use App\Domain\RiskManager\Values\RiskManagerDeletedAt;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Domain\RiskManager\Values\RiskManagerName;
use App\Domain\RiskManager\Values\RiskManagerDescription;
use App\Domain\RiskManager\Values\RiskManagerItemId;
use App\Domain\RiskManager\Values\RiskManagerRelationships;
use App\Domain\RiskManager\Values\RiskManagerSettings;
use App\Domain\RiskManager\Values\RiskManagerUpdatedAt;
use App\Http\Requests\RiskManager\RiskManagerAllRequest;
use App\Models\RiskManagerAffiliation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RiskManagerRepository implements RiskManagerContract
{
    private $riskManagerModel;

    public function __construct()
    {
        $this->riskManagerModel = new RiskManagerModel;
    }

    public function all(string|null $search = null, int|null $perPage, RiskManagerAllRequest $request): LengthAwarePaginator
    {
        try {
            $riskManagers = $this->riskManagerModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'countries'])
                ->where('item_id', 24)
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereHas('countries', fn($r) => $r->where('id', $request->integer('country_id')));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $riskManagers->paginate($perPage ?? $riskManagers->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las administradoras de riegos laborales: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las administradoras de riegos laborales: ' . $e->getMessage());
        }
    }

    public function find(RiskManagerId $riskManagerId): ?RiskManager
    {
        try {
            $model = $this->riskManagerModel->withTrashed()
                ->with(['item', 'countries'])->where('item_id', 24)
                ->whereKey($riskManagerId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La administradora de riesgo laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    public function store(RiskManager $riskManager): RiskManager
    {
        try {
            $model = $this->riskManagerModel
                ->create($this->mapToModelData($riskManager));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    public function update(RiskManager $riskManager, RiskManagerId $riskManagerId): RiskManager
    {
        try {
            $model = tap($this->riskManagerModel->where('item_id', 24)->whereKey($riskManagerId->value())->firstOrFail())
                ->update($this->mapToModelData($riskManager))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La administradora de riesgo laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    public function toggle(RiskManager $riskManager, RiskManagerId $riskManagerId): RiskManager
    {
        try {
            $model = $this->riskManagerModel->where('item_id', 24)->whereKey($riskManagerId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $riskManager->countryId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La administradora de riesgo laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    public function delete(RiskManagerId $riskManagerId): RiskManager
    {
        try {
            $model = $this->riskManagerModel->where('item_id', 24)->whereKey($riskManagerId->value())->firstOrFail();

            $isUsed = RiskManagerAffiliation::where('risk_manager_id', $riskManagerId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La administradora de riesgo laboral no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    public function restore(RiskManagerId $riskManagerId): RiskManager
    {
        try {
            $model = tap($this->riskManagerModel->withTrashed()->where('item_id', 24)->whereKey($riskManagerId->value())->firstOrFail(), fn($riskManager) => $riskManager->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La administradora de riesgo laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): RiskManager
    {
        return new RiskManager(
            new RiskManagerId($model->id),
            new RiskManagerItemId($model->item_id),
            new RiskManagerName($model->name),
            new RiskManagerDescription($model->description),
            new RiskManagerSettings($model->settings),
            new RiskManagerCountryId(),
            new RiskManagerCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new RiskManagerUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new RiskManagerDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new RiskManagerRelationships($model->countries)
        );
    }

    private function mapToModelData(RiskManager $riskManager): array
    {
        $array = [];

        if ($riskManager->itemId()->value()) {
            $array['item_id'] = $riskManager->itemId()->value();
        }

        if ($riskManager->name()->value()) {
            $array['name'] = $riskManager->name()->value();
        }

        if ($riskManager->description()->value()) {
            $array['description'] = $riskManager->description()->value();
        }

        if ($riskManager->settings()->value()) {
            $array['settings'] = $riskManager->settings()->value();
        }

        return $array;
    }
}
