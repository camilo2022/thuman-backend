<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\CIEChapter\CIEGroup\Contracts\CIEGroupContract;
use App\Models\CIEGroup as CIEGroupModel;
use App\Domain\CIEChapter\CIEGroup\CIEGroup;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupCreatedAt;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupDeletedAt;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupName;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupDescription;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupItemId;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupCIEChapterId;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupRelationships;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupSettings;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupUpdatedAt;
use App\Domain\CIEChapter\Values\CIEChapterId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CIEGroupRepository implements CIEGroupContract
{
    private $cieGroupModel;

    public function __construct()
    {
        $this->cieGroupModel = new CIEGroupModel;
    }

    public function all(string|null $search = null, int|null $perPage, CIEChapterId $cieChapterId): LengthAwarePaginator
    {
        try {
            $cieGroups = $this->cieGroupModel->withTrashed()
                ->with(['item', 'cie_chapter'])
                ->where('item_id', 49)
                ->whereHas('cie_chapter', fn ($r) => $r->where('id', $cieChapterId->value()))
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $cieGroups->paginate($perPage ?? $cieGroups->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los grupos de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los grupos de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        }
    }

    public function find(CIEGroupId $cieGroupId): ?CIEGroup
    {
        try {
            $model = $this->cieGroupModel->withTrashed()
                ->with(['item', 'cie_chapter'])->where('item_id', 49)
                ->whereKey($cieGroupId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El grupo de CIE - Clasificación Internacional de Enfermedades no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el grupo de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el grupo de CIE - Clasificación Internacional de Enfermedades: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): CIEGroup
    {
        return new CIEGroup(
            new CIEGroupId($model->id),
            new CIEGroupCIEChapterId($model->cie_chapter?->first()?->id),
            new CIEGroupItemId($model->item?->id ?? $model->item_id),
            new CIEGroupName($model->name),
            new CIEGroupDescription($model->description),
            new CIEGroupSettings($model->settings),
            new CIEGroupCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CIEGroupUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CIEGroupDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new CIEGroupRelationships($model->cie_chapter?->first())
        );
    }
}
