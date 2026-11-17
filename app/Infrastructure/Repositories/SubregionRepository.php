<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Location\Subregion\Contracts\SubregionContract;
use App\Models\Subregion as SubregionModel;
use App\Domain\Location\Subregion\Subregion;
use App\Domain\Location\Subregion\Values\SubregionCreatedAt;
use App\Domain\Location\Subregion\Values\SubregionId;
use App\Domain\Location\Subregion\Values\SubregionName;
use App\Domain\Location\Subregion\Values\SubregionRegionId;
use App\Domain\Location\Subregion\Values\SubregionTranslations;
use App\Domain\Location\Subregion\Values\SubregionUpdatedAt;
use App\Http\Requests\Location\Subregion\SubregionAllRequest;
use App\Http\Requests\Location\Subregion\SubregionFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SubregionRepository implements SubregionContract
{
    private $subregionModel;

    public function __construct()
    {
        $this->subregionModel = new SubregionModel;
    }

    public function all(string|null $search = null, int|null $perPage, SubregionAllRequest $request): LengthAwarePaginator
    {
        try {
            $subregions = $this->subregionModel
                ->with(['region', 'countries'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('region_id'), fn($query) => $query->where('region_id', $request->input('region_id')))
                ->orderBy('name', 'asc');

            return $subregions->paginate($perPage ?? $subregions->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las subregiones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las subregiones: ' . $e->getMessage());
        }
    }

    public function find(SubregionFindRequest $request, SubregionId $subregionId): ?Subregion
    {
        try {
            $region = $this->subregionModel
                ->when($subregionId->value(), function ($query) use ($subregionId) {
                    $query->where('id', $subregionId->value());
                })
                ->when(!$subregionId->value(), function ($query) use ($request) {
                    $query->when($request->filled('name'), function ($query) use ($request) {
                        $query->where('name', $request->input('name'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($region);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La subregion no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la subregion: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la subregion: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Subregion
    {
        return new Subregion(
            new SubregionId($model->id),
            new SubregionRegionId($model->region_id),
            new SubregionName($model->name),
            new SubregionTranslations($model->translations),
            new SubregionCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SubregionUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }
}
