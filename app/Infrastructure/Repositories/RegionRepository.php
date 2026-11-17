<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Location\Region\Contracts\RegionContract;
use App\Models\Region as RegionModel;
use App\Domain\Location\Region\Region;
use App\Domain\Location\Region\Values\RegionCreatedAt;
use App\Domain\Location\Region\Values\RegionId;
use App\Domain\Location\Region\Values\RegionName;
use App\Domain\Location\Region\Values\RegionTranslations;
use App\Domain\Location\Region\Values\RegionUpdatedAt;
use App\Http\Requests\Location\Region\RegionFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RegionRepository implements RegionContract
{
    private $regionModel;

    public function __construct()
    {
        $this->regionModel = new RegionModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $regions = $this->regionModel
                ->with(['subregions'])
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'name');

            return $regions->paginate($perPage ?? $regions->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las regiones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las regiones: ' . $e->getMessage());
        }
    }

    public function find(RegionFindRequest $request, RegionId $regionId): ?Region
    {
        try {
            $region = $this->regionModel
                ->when($regionId->value(), function ($query) use ($regionId) {
                    $query->where('id', $regionId->value());
                })
                ->when(!$regionId->value(), function ($query) use ($request) {
                    $query->when($request->filled('name'), function ($query) use ($request) {
                        $query->where('name', $request->input('name'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($region);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La region no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la region: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la region: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Region
    {
        return new Region(
            new RegionId($model->id),
            new RegionName($model->name),
            new RegionTranslations($model->translations),
            new RegionCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new RegionUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }
}
