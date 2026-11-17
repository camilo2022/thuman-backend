<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Contracts\CIECodeContract;
use App\Models\CIECode as CIECodeModel;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\CIECode;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeCreatedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeDeletedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeName;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeDescription;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeItemId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeCIESubgroupId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeRelationships;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeSettings;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeUpdatedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CIECodeRepository implements CIECodeContract
{
    private $cieCodeModel;

    public function __construct()
    {
        $this->cieCodeModel = new CIECodeModel;
    }

    public function all(string|null $search = null, int|null $perPage, CIESubgroupId $cieSubgroupId): LengthAwarePaginator
    {
        try {
            $cieCodes = $this->cieCodeModel->withTrashed()
                ->with(['item', 'cie_subgroup'])
                ->where('item_id', 51)
                ->when($cieSubgroupId->value(), function ($query) use ($cieSubgroupId) {
                    $query->whereHas('cie_subgroup', fn ($r) => $r->where('id', $cieSubgroupId->value()));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $cieCodes->paginate($perPage ?? $cieCodes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los codigos de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los codigos de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        }
    }

    public function find(CIECodeId $cieCodeId): ?CIECode
    {
        try {
            $model = $this->cieCodeModel->withTrashed()
                ->with(['item', 'cie_subgroup'])->where('item_id', 51)
                ->whereKey($cieCodeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El codigo de CIE - Clasificación Internacional de Enfermedades no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el codigo de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el codigo de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): CIECode
    {
        return new CIECode(
            new CIECodeId($model->id),
            new CIECodeCIESubgroupId($model->cie_subgroup?->first()?->id),
            new CIECodeItemId($model->item?->id ?? $model->item_id),
            new CIECodeName($model->name),
            new CIECodeDescription($model->description),
            new CIECodeSettings($model->settings),
            new CIECodeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CIECodeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CIECodeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new CIECodeRelationships($model->cie_subgroup?->first())
        );
    }
}
