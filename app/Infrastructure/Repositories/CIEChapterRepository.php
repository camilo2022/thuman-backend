<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\CIEChapter\Contracts\CIEChapterContract;
use App\Models\CIEChapter as CIEChapterModel;
use App\Domain\CIEChapter\CIEChapter;
use App\Domain\CIEChapter\Values\CIEChapterCreatedAt;
use App\Domain\CIEChapter\Values\CIEChapterDeletedAt;
use App\Domain\CIEChapter\Values\CIEChapterId;
use App\Domain\CIEChapter\Values\CIEChapterName;
use App\Domain\CIEChapter\Values\CIEChapterDescription;
use App\Domain\CIEChapter\Values\CIEChapterItemId;
use App\Domain\CIEChapter\Values\CIEChapterSettings;
use App\Domain\CIEChapter\Values\CIEChapterUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CIEChapterRepository implements CIEChapterContract
{
    private $cieChapterModel;

    public function __construct()
    {
        $this->cieChapterModel = new CIEChapterModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $cieChapters = $this->cieChapterModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 48)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $cieChapters->paginate($perPage ?? $cieChapters->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los capitulos de CIE - Clasificación Internacional de Enfermedades:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los capitulos de CIE - Clasificación Internacional de Enfermedades:' . $e->getMessage());
        }
    }

    public function find(CIEChapterId $cieChapterId): ?CIEChapter
    {
        try {
            $model = $this->cieChapterModel->withTrashed()
                ->with(['item'])->where('item_id', 48)
                ->whereKey($cieChapterId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El capitulo de CIE - Clasificación Internacional de Enfermedades no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el capitulo de CIE - Clasificación Internacional de Enfermedades:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el capitulo de CIE - Clasificación Internacional de Enfermedades:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): CIEChapter
    {
        return new CIEChapter(
            new CIEChapterId($model->id),
            new CIEChapterItemId($model->item?->id ?? $model->item_id),
            new CIEChapterName($model->name),
            new CIEChapterDescription($model->description),
            new CIEChapterSettings($model->settings),
            new CIEChapterCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CIEChapterUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CIEChapterDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
