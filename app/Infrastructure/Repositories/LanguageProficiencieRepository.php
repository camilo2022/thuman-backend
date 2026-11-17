<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Profile\LanguageProficiencie\Contracts\LanguageProficiencieContract;
use App\Domain\Profile\LanguageProficiencie\File;
use App\Models\LanguageProficiencie as LanguageProficiencieModel;
use App\Models\File as FileModel;
use App\Domain\Profile\LanguageProficiencie\LanguageProficiencie;
use App\Domain\Profile\LanguageProficiencie\Values\FileCreatedAt;
use App\Domain\Profile\LanguageProficiencie\Values\FileDeletedAt;
use App\Domain\Profile\LanguageProficiencie\Values\FileExtension;
use App\Domain\Profile\LanguageProficiencie\Values\FileFileSubtypeId;
use App\Domain\Profile\LanguageProficiencie\Values\FileFileTypeId;
use App\Domain\Profile\LanguageProficiencie\Values\FileId;
use App\Domain\Profile\LanguageProficiencie\Values\FileMetadata;
use App\Domain\Profile\LanguageProficiencie\Values\FileMime;
use App\Domain\Profile\LanguageProficiencie\Values\FileModelId;
use App\Domain\Profile\LanguageProficiencie\Values\FileModelType;
use App\Domain\Profile\LanguageProficiencie\Values\FileName;
use App\Domain\Profile\LanguageProficiencie\Values\FilePath;
use App\Domain\Profile\LanguageProficiencie\Values\FileSize;
use App\Domain\Profile\LanguageProficiencie\Values\FileUpdatedAt;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieCreatedAt;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieLanguageId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieLanguageLevelId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieProfileId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieRelationships;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieUpdatedAt;
use App\Domain\Profile\Values\ProfileId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LanguageProficiencieRepository implements LanguageProficiencieContract
{
    private $languageProficiencieModel;
    private $fileModel;

    public function __construct()
    {
        $this->languageProficiencieModel = new LanguageProficiencieModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, ProfileId $profileId): LengthAwarePaginator
    {
        try {
            $languageProficiencies = $this->languageProficiencieModel
                ->with(['profile', 'language', 'language_level', 'file'])
                ->where('profile_id', $profileId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $languageProficiencies->paginate($perPage ?? $languageProficiencies->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los idiomas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los idiomas: ' . $e->getMessage());
        }
    }

    public function find(LanguageProficiencieId $languageProficiencieId): ?LanguageProficiencie
    {
        try {
            $model = $this->languageProficiencieModel
                ->with(['profile', 'language', 'language_level', 'file'])
                ->findOrFail($languageProficiencieId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El idioma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el idioma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el idioma: ' . $e->getMessage());
        }
    }

    public function store(LanguageProficiencie $languageProficiencie): LanguageProficiencie
    {
        try {
            $model = $this->languageProficiencieModel
                ->create($this->mapToModelData($languageProficiencie));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el idioma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el idioma: ' . $e->getMessage());
        }
    }

    public function update(LanguageProficiencie $languageProficiencie, LanguageProficiencieId $languageProficiencieId): LanguageProficiencie
    {
        try {
            $model = tap($this->languageProficiencieModel->findOrFail($languageProficiencieId->value()))
                ->update($this->mapToModelData($languageProficiencie))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El idioma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el idioma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el idioma: ' . $e->getMessage());
        }
    }

    public function delete(LanguageProficiencieId $languageProficiencieId): void
    {
        try {
            $this->languageProficiencieModel->findOrFail($languageProficiencieId->value())
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El idioma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el idioma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el idioma: ' . $e->getMessage());
        }
    }

    public function file(File $file): File
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataFile($file, 'keys'), $this->mapToModelDataFile($file, 'data'));

            return $this->mapToEntityFile($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El archivo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar el archivo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar el archivo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): LanguageProficiencie
    {
        return new LanguageProficiencie(
            new LanguageProficiencieId($model->id),
            new LanguageProficiencieProfileId($model->profile?->id ?? $model->profile_id),
            new LanguageProficiencieLanguageId($model->language?->id ?? $model->language_id),
            new LanguageProficiencieLanguageLevelId($model->language_level?->id ?? $model->language_level_id),
            new LanguageProficiencieCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new LanguageProficiencieUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new LanguageProficiencieRelationships($model->profile, $model->language, $model->file)
        );
    }

    private function mapToEntityFile($model): File
    {
        return new File(
            new FileId($model->id),
            new FileModelId($model->model?->id ?? $model->model_id),
            new FileModelType($model->model_type),
            new FileFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new FileFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new FileName($model->name),
            new FilePath($model->path),
            new FileMime($model->mime),
            new FileExtension($model->extension),
            new FileSize($model->size),
            new FileMetadata($model->metadata),
            new FileCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FileUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FileDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(LanguageProficiencie $languageProficiencie): array
    {
        $array = [];

        if ($languageProficiencie->profileId()->value()) {
            $array['profile_id'] = $languageProficiencie->profileId()->value();
        }

        if ($languageProficiencie->languageId()->value()) {
            $array['language_id'] = $languageProficiencie->languageId()->value();
        }

        if ($languageProficiencie->languageLevelId()->value()) {
            $array['language_level_id'] = $languageProficiencie->languageLevelId()->value();
        }

        return $array;
    }

    private function mapToModelDataFile(File $file, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($file->modelId()->value()) {
            $array['keys']['model_id'] = $file->modelId()->value();
        }

        if ($file->modelType()->value()) {
            $array['keys']['model_type'] = $file->modelType()->value();
        }

        if ($file->fileTypeId()->value()) {
            $array['data']['file_type_id'] = $file->fileTypeId()->value();
        }

        if ($file->fileSubtypeId()->value()) {
            $array['data']['file_subtype_id'] = $file->fileSubtypeId()->value();
        }

        if ($file->name()->value()) {
            $array['data']['name'] = $file->name()->value();
        }

        if ($file->path()->value()) {
            $array['data']['path'] = $file->path()->value();
        }

        if ($file->mime()->value()) {
            $array['data']['mime'] = $file->mime()->value();
        }

        if ($file->extension()->value()) {
            $array['data']['extension'] = $file->extension()->value();
        }

        if ($file->size()->value()) {
            $array['data']['size'] = $file->size()->value();
        }

        if ($file->metadata()->value()) {
            $array['data']['metadata'] = $file->metadata()->value();
        }

        return $array[$index];
    }
}
