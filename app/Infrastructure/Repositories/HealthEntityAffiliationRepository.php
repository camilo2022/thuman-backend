<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\HealthEntityAffiliation\Contracts\HealthEntityAffiliationContract;
use App\Domain\HealthEntityAffiliation\File;
use App\Models\HealthEntityAffiliation as HealthEntityAffiliationModel;
use App\Models\File as FileModel;
use App\Domain\HealthEntityAffiliation\HealthEntityAffiliation;
use App\Domain\HealthEntityAffiliation\Values\FileCreatedAt;
use App\Domain\HealthEntityAffiliation\Values\FileDeletedAt;
use App\Domain\HealthEntityAffiliation\Values\FileExtension;
use App\Domain\HealthEntityAffiliation\Values\FileFileSubtypeId;
use App\Domain\HealthEntityAffiliation\Values\FileFileTypeId;
use App\Domain\HealthEntityAffiliation\Values\FileId;
use App\Domain\HealthEntityAffiliation\Values\FileMetadata;
use App\Domain\HealthEntityAffiliation\Values\FileMime;
use App\Domain\HealthEntityAffiliation\Values\FileModelId;
use App\Domain\HealthEntityAffiliation\Values\FileModelType;
use App\Domain\HealthEntityAffiliation\Values\FileName;
use App\Domain\HealthEntityAffiliation\Values\FilePath;
use App\Domain\HealthEntityAffiliation\Values\FileSize;
use App\Domain\HealthEntityAffiliation\Values\FileUpdatedAt;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationCountryId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationCreatedAt;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationPersonId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationHealthEntityId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationObservation;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationRelationships;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationStatus;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationUpdatedAt;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class HealthEntityAffiliationRepository implements HealthEntityAffiliationContract
{
    private $healthEntityAffiliationModel;
    private $fileModel;

    public function __construct()
    {
        $this->healthEntityAffiliationModel = new HealthEntityAffiliationModel;
        $this->fileModel = new FileModel;
    }

    public function find(HealthEntityAffiliationFindRequest $request, HealthEntityAffiliationId $healthEntityAffiliationId): ?HealthEntityAffiliation
    {
        try {
            $model = $this->healthEntityAffiliationModel
                ->with(['person', 'country', 'health_entity', 'file'])
                ->when($healthEntityAffiliationId->value(), function ($query) use ($healthEntityAffiliationId) {
                    $query->where('id', $healthEntityAffiliationId->value());
                })
                ->when(!$healthEntityAffiliationId->value(), function ($query) use ($request) {
                    $query->when($request->filled('person_id'), function ($query) use ($request) {
                        $query->where('person_id', $request->integer('person_id'));
                    })
                    ->when($request->filled('profile_id'), function ($query) use ($request) {
                        $query->whereHas('person.profile', fn ($q) => $q->where('id', $request->integer('profile_id')));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La afiliación a la entidad de salud no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la afiliación a la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la afiliación a la entidad de salud: ' . $e->getMessage());
        }
    }

    public function store(HealthEntityAffiliation $healthEntityAffiliation): HealthEntityAffiliation
    {
        try {
            $model = $this->healthEntityAffiliationModel
                ->create($this->mapToModelData($healthEntityAffiliation));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la afiliación a la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la afiliación a la entidad de salud: ' . $e->getMessage());
        }
    }

    public function update(HealthEntityAffiliation $healthEntityAffiliation, HealthEntityAffiliationId $healthEntityAffiliationId): HealthEntityAffiliation
    {
        try {
            $model = tap($this->healthEntityAffiliationModel->findOrFail($healthEntityAffiliationId->value()))
                ->update($this->mapToModelData($healthEntityAffiliation))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La afiliación a la entidad de salud no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la afiliación a la entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la afiliación a la entidad de salud: ' . $e->getMessage());
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

    private function mapToEntity($model): HealthEntityAffiliation
    {
        return new HealthEntityAffiliation(
            new HealthEntityAffiliationId($model->id),
            new HealthEntityAffiliationPersonId($model->person?->id ?? $model->person_id),
            new HealthEntityAffiliationCountryId($model->country?->id ?? $model->country_id),
            new HealthEntityAffiliationHealthEntityId($model->health_entity_id?->id ?? $model->health_entity_id),
            new HealthEntityAffiliationObservation($model->observation),
            new HealthEntityAffiliationStatus($model->status),
            new HealthEntityAffiliationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new HealthEntityAffiliationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new HealthEntityAffiliationRelationships($model->person, $model->country, $model->health_entity, $model->file)
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

    private function mapToModelData(HealthEntityAffiliation $healthEntityAffiliation): array
    {
        $array = [];

        if ($healthEntityAffiliation->personId()->value()) {
            $array['person_id'] = $healthEntityAffiliation->personId()->value();
        }

        if ($healthEntityAffiliation->countryId()->value()) {
            $array['country_id'] = $healthEntityAffiliation->countryId()->value();
        }

        if ($healthEntityAffiliation->healthEntityId()->value()) {
            $array['health_entity_id'] = $healthEntityAffiliation->healthEntityId()->value();
        }

        if ($healthEntityAffiliation->observation()->value()) {
            $array['observation'] = $healthEntityAffiliation->observation()->value();
        }

        if ($healthEntityAffiliation->status()->value()) {
            $array['status'] = $healthEntityAffiliation->status()->value();
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
            $array['keys']['file_type_id'] = $file->fileTypeId()->value();
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
