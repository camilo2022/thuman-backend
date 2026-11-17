<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Language\Contracts\LanguageContract;
use App\Models\Language as LanguageModel;
use App\Domain\Language\Language;
use App\Domain\Language\Values\LanguageCreatedAt;
use App\Domain\Language\Values\LanguageDeletedAt;
use App\Domain\Language\Values\LanguageId;
use App\Domain\Language\Values\LanguageName;
use App\Domain\Language\Values\LanguageDescription;
use App\Domain\Language\Values\LanguageItemId;
use App\Domain\Language\Values\LanguageSettings;
use App\Domain\Language\Values\LanguageUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LanguageRepository implements LanguageContract
{
    private $languageModel;

    public function __construct()
    {
        $this->languageModel = new LanguageModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $languages = $this->languageModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 21)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $languages->paginate($perPage ?? $languages->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los idiomas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los idiomas: ' . $e->getMessage());
        }
    }

    public function find(LanguageId $languageId): ?Language
    {
        try {
            $model = $this->languageModel->withTrashed()
                ->with(['item'])->where('item_id', 21)
                ->whereKey($languageId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El idioma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el idioma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el idioma: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Language
    {
        return new Language(
            new LanguageId($model->id),
            new LanguageItemId($model->item?->id ?? $model->item_id),
            new LanguageName($model->name),
            new LanguageDescription($model->description),
            new LanguageSettings($model->settings),
            new LanguageCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new LanguageUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new LanguageDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
