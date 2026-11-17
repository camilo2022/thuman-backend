<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Contracts\CIESubgroupContract;
use App\Models\CIESubgroup as CIESubgroupModel;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIESubgroup;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupCreatedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupDeletedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupName;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupDescription;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupItemId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupCIEGroupId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupRelationships;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupSettings;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupUpdatedAt;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CIESubgroupRepository implements CIESubgroupContract
{
    private $cieSubgroupModel;

    public function __construct()
    {
        $this->cieSubgroupModel = new CIESubgroupModel;
    }

    public function all(string|null $search = null, int|null $perPage, CIEGroupId $cieGroupId): LengthAwarePaginator
    {
        try {
            $cieSubgroups = $this->cieSubgroupModel->withTrashed()
                ->with(['item', 'cie_group'])
                ->where('item_id', 50)
                ->whereHas('cie_group', fn ($r) => $r->where('id', $cieGroupId->value()))
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $cieSubgroups->paginate($perPage ?? $cieSubgroups->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los subgrupos de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los subgrupos de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        }
    }

    public function find(CIESubgroupId $cieSubgroupId): ?CIESubgroup
    {
        try {
            $model = $this->cieSubgroupModel->withTrashed()
                ->with(['item', 'cie_group'])->where('item_id', 50)
                ->whereKey($cieSubgroupId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El subgrupo de CIE - Clasificación Internacional de Enfermedades no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el subgrupo de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el subgrupo de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): CIESubgroup
    {
        return new CIESubgroup(
            new CIESubgroupId($model->id),
            new CIESubgroupCIEGroupId($model->cie_group?->first()?->id),
            new CIESubgroupItemId($model->item?->id ?? $model->item_id),
            new CIESubgroupName($model->name),
            new CIESubgroupDescription($model->description),
            new CIESubgroupSettings($model->settings),
            new CIESubgroupCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CIESubgroupUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CIESubgroupDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new CIESubgroupRelationships($model->cie_group?->first())
        );
    }
}
