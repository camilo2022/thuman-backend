<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\RiskManagerAffiliation\Contracts\RiskManagerAffiliationContract;
use App\Domain\RiskManagerAffiliation\File;
use App\Models\RiskManagerAffiliation as RiskManagerAffiliationModel;
use App\Models\File as FileModel;
use App\Domain\RiskManagerAffiliation\RiskManagerAffiliation;
use App\Domain\RiskManagerAffiliation\Values\FileCreatedAt;
use App\Domain\RiskManagerAffiliation\Values\FileDeletedAt;
use App\Domain\RiskManagerAffiliation\Values\FileExtension;
use App\Domain\RiskManagerAffiliation\Values\FileFileSubtypeId;
use App\Domain\RiskManagerAffiliation\Values\FileFileTypeId;
use App\Domain\RiskManagerAffiliation\Values\FileId;
use App\Domain\RiskManagerAffiliation\Values\FileMetadata;
use App\Domain\RiskManagerAffiliation\Values\FileMime;
use App\Domain\RiskManagerAffiliation\Values\FileModelId;
use App\Domain\RiskManagerAffiliation\Values\FileModelType;
use App\Domain\RiskManagerAffiliation\Values\FileName;
use App\Domain\RiskManagerAffiliation\Values\FilePath;
use App\Domain\RiskManagerAffiliation\Values\FileSize;
use App\Domain\RiskManagerAffiliation\Values\FileUpdatedAt;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationCountryId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationCreatedAt;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationObservation;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationPersonId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationRiskManagerId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationRelationships;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationStatus;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationUpdatedAt;
use App\Http\Requests\RiskManagerAffiliation\RiskManagerAffiliationFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RiskManagerAffiliationRepository implements RiskManagerAffiliationContract
{
    private $riskManagerAffiliationModel;
    private $fileModel;

    public function __construct()
    {
        $this->riskManagerAffiliationModel = new RiskManagerAffiliationModel;
        $this->fileModel = new FileModel;
    }

    public function find(RiskManagerAffiliationFindRequest $request, RiskManagerAffiliationId $riskManagerAffiliationId): ?RiskManagerAffiliation
    {
        try {
            $model = $this->riskManagerAffiliationModel
                ->with(['person', 'country', 'risk_manager', 'file'])
                ->when($riskManagerAffiliationId->value(), function ($query) use ($riskManagerAffiliationId) {
                    $query->where('id', $riskManagerAffiliationId->value());
                })
                ->when(!$riskManagerAffiliationId->value(), function ($query) use ($request) {
                    $query->when($request->filled('person_id'), function ($query) use ($request) {
                        $query->where('person_id', $request->input('person_id'));
                    })
                    ->when($request->filled('profile_id'), function ($query) use ($request) {
                        $query->whereHas('person.profile', fn ($q) => $q->where('id', $request->integer('profile_id')));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La afiliación a la administradora de riesgo laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la afiliación a la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la afiliación a la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    public function store(RiskManagerAffiliation $riskManagerAffiliation): RiskManagerAffiliation
    {
        try {
            $model = $this->riskManagerAffiliationModel
                ->create($this->mapToModelData($riskManagerAffiliation));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la afiliación a la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la afiliación a la administradora de riesgo laboral: ' . $e->getMessage());
        }
    }

    public function update(RiskManagerAffiliation $riskManagerAffiliation, RiskManagerAffiliationId $riskManagerAffiliationId): RiskManagerAffiliation
    {
        try {
            $model = tap($this->riskManagerAffiliationModel->findOrFail($riskManagerAffiliationId->value()))
                ->update($this->mapToModelData($riskManagerAffiliation))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La afiliación a la administradora de riesgo laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la afiliación a la administradora de riesgo laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la afiliación a la administradora de riesgo laboral: ' . $e->getMessage());
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

    private function mapToEntity($model): RiskManagerAffiliation
    {
        return new RiskManagerAffiliation(
            new RiskManagerAffiliationId($model->id),
            new RiskManagerAffiliationPersonId($model->person?->id ?? $model->person_id),
            new RiskManagerAffiliationCountryId($model->country?->id ?? $model->country_id),
            new RiskManagerAffiliationRiskManagerId($model->risk_manager_id?->id ?? $model->risk_manager_id),
            new RiskManagerAffiliationObservation($model->observation),
            new RiskManagerAffiliationStatus($model->status),
            new RiskManagerAffiliationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new RiskManagerAffiliationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new RiskManagerAffiliationRelationships($model->person, $model->country, $model->risk_manager, $model->file)
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

    private function mapToModelData(RiskManagerAffiliation $riskManagerAffiliation): array
    {
        $array = [];

        if ($riskManagerAffiliation->personId()->value()) {
            $array['person_id'] = $riskManagerAffiliation->personId()->value();
        }

        if ($riskManagerAffiliation->countryId()->value()) {
            $array['country_id'] = $riskManagerAffiliation->countryId()->value();
        }

        if ($riskManagerAffiliation->riskManagerId()->value()) {
            $array['risk_manager_id'] = $riskManagerAffiliation->riskManagerId()->value();
        }

        if ($riskManagerAffiliation->observation()->value()) {
            $array['observation'] = $riskManagerAffiliation->observation()->value();
        }

        if ($riskManagerAffiliation->status()->value()) {
            $array['status'] = $riskManagerAffiliation->status()->value();
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
