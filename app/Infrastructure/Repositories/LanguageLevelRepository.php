<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\LanguageLevel\Contracts\LanguageLevelContract;
use App\Models\LanguageLevel as LanguageLevelModel;
use App\Domain\LanguageLevel\LanguageLevel;
use App\Domain\LanguageLevel\Values\LanguageLevelCreatedAt;
use App\Domain\LanguageLevel\Values\LanguageLevelDeletedAt;
use App\Domain\LanguageLevel\Values\LanguageLevelId;
use App\Domain\LanguageLevel\Values\LanguageLevelName;
use App\Domain\LanguageLevel\Values\LanguageLevelDescription;
use App\Domain\LanguageLevel\Values\LanguageLevelItemId;
use App\Domain\LanguageLevel\Values\LanguageLevelSettings;
use App\Domain\LanguageLevel\Values\LanguageLevelUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LanguageLevelRepository implements LanguageLevelContract
{
    private $languageLevelModel;

    public function __construct()
    {
        $this->languageLevelModel = new LanguageLevelModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $languageLevels = $this->languageLevelModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 20)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $languageLevels->paginate($perPage ?? $languageLevels->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los niveles de idiomas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los niveles de idiomas: ' . $e->getMessage());
        }
    }

    public function find(LanguageLevelId $languageLevelId): ?LanguageLevel
    {
        try {
            $model = $this->languageLevelModel->withTrashed()
                ->with(['item'])->where('item_id', 20)
                ->whereKey($languageLevelId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El niveles de idioma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el niveles de idioma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el niveles de idioma: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): LanguageLevel
    {
        return new LanguageLevel(
            new LanguageLevelId($model->id),
            new LanguageLevelItemId($model->item?->id ?? $model->item_id),
            new LanguageLevelName($model->name),
            new LanguageLevelDescription($model->description),
            new LanguageLevelSettings($model->settings),
            new LanguageLevelCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new LanguageLevelUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new LanguageLevelDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
