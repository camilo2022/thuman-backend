<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Enterprise\Entity\Ally\Campaign\Contracts\CampaignContract;
use App\Models\Campaign as CampaignModel;
use App\Domain\Enterprise\Entity\Ally\Campaign\Campaign;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignCreatedAt;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignDeletedAt;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignAllyId;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignId;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignName;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignDescription;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignRelationships;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignUpdatedAt;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Http\Requests\Enterprise\Entity\Ally\Campaign\CampaignAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CampaignRepository implements CampaignContract
{
    private $campaignModel;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel;
    }

    public function all(string|null $search = null, int|null $perPage, AllyId $allyId, CampaignAllRequest $request): LengthAwarePaginator
    {
        try {
            $campaigns = $this->campaignModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['allie'])
                ->where('ally_id', $allyId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $campaigns->paginate($perPage ?? $campaigns->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las campañas del aliado: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las campañas del aliado: ' . $e->getMessage());
        }
    }

    public function find(CampaignId $campaignId): ?Campaign
    {
        try {
            $model = $this->campaignModel->withTrashed()
                ->with(['allie'])
                ->findOrFail($campaignId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La campaña del aliado no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la campaña del aliado: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la campaña del aliado: ' . $e->getMessage());
        }
    }

    public function store(Campaign $campaign): Campaign
    {
        try {
            $model = $this->campaignModel
                ->create($this->mapToModelData($campaign));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function update(Campaign $campaign, CampaignId $campaignId): Campaign
    {
        try {
            $model = tap($this->campaignModel->findOrFail($campaignId->value()))
                ->update($this->mapToModelData($campaign))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function delete(CampaignId $campaignId): Campaign
    {
        try {
            $model = tap($this->campaignModel->findOrFail($campaignId->value()), fn($campaign) => $campaign->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function restore(CampaignId $campaignId): Campaign
    {
        try {
            $model = tap($this->campaignModel->withTrashed()->findOrFail($campaignId->value()), fn($campaign) => $campaign->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la sede de la entidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Campaign
    {
        return new Campaign(
            new CampaignId($model->id),
            new CampaignAllyId($model->allie?->id ?? $model->ally_id),
            new CampaignName($model->name),
            new CampaignDescription($model->description),
            new CampaignCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CampaignUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CampaignDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new CampaignRelationships($model->allie)
        );
    }

    private function mapToModelData(Campaign $campaign): array
    {
        $array = [];

        if ($campaign->allyId()->value()) {
            $array['ally_id'] = $campaign->allyId()->value();
        }

        if ($campaign->name()->value()) {
            $array['name'] = $campaign->name()->value();
        }

        if ($campaign->description()->value()) {
            $array['description'] = $campaign->description()->value();
        }

        return $array;
    }
}
