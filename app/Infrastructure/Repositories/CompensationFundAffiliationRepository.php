<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\CompensationFundAffiliation\Contracts\CompensationFundAffiliationContract;
use App\Domain\CompensationFundAffiliation\File;
use App\Models\CompensationFundAffiliation as CompensationFundAffiliationModel;
use App\Models\File as FileModel;
use App\Domain\CompensationFundAffiliation\CompensationFundAffiliation;
use App\Domain\CompensationFundAffiliation\Values\FileCreatedAt;
use App\Domain\CompensationFundAffiliation\Values\FileDeletedAt;
use App\Domain\CompensationFundAffiliation\Values\FileExtension;
use App\Domain\CompensationFundAffiliation\Values\FileFileSubtypeId;
use App\Domain\CompensationFundAffiliation\Values\FileFileTypeId;
use App\Domain\CompensationFundAffiliation\Values\FileId;
use App\Domain\CompensationFundAffiliation\Values\FileMetadata;
use App\Domain\CompensationFundAffiliation\Values\FileMime;
use App\Domain\CompensationFundAffiliation\Values\FileModelId;
use App\Domain\CompensationFundAffiliation\Values\FileModelType;
use App\Domain\CompensationFundAffiliation\Values\FileName;
use App\Domain\CompensationFundAffiliation\Values\FilePath;
use App\Domain\CompensationFundAffiliation\Values\FileSize;
use App\Domain\CompensationFundAffiliation\Values\FileUpdatedAt;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCountryId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCreatedAt;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationPersonId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCompensationFundId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationObservation;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationRelationships;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationStatus;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationUpdatedAt;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CompensationFundAffiliationRepository implements CompensationFundAffiliationContract
{
    private $compensationFundAffiliationModel;
    private $fileModel;

    public function __construct()
    {
        $this->compensationFundAffiliationModel = new CompensationFundAffiliationModel;
        $this->fileModel = new FileModel;
    }

    public function find(CompensationFundAffiliationFindRequest $request, CompensationFundAffiliationId $compensationFundAffiliationId): ?CompensationFundAffiliation
    {
        try {
            $model = $this->compensationFundAffiliationModel
                ->with(['person', 'country', 'compensation_fund', 'file'])
                ->when($compensationFundAffiliationId->value(), function ($query) use ($compensationFundAffiliationId) {
                    $query->where('id', $compensationFundAffiliationId->value());
                })
                ->when(!$compensationFundAffiliationId->value(), function ($query) use ($request) {
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
            throw new HttpException(404, 'La afiliación a la caja de compensación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la afiliación a la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la afiliación a la caja de compensación: ' . $e->getMessage());
        }
    }

    public function store(CompensationFundAffiliation $compensationFundAffiliation): CompensationFundAffiliation
    {
        try {
            $model = $this->compensationFundAffiliationModel
                ->create($this->mapToModelData($compensationFundAffiliation));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la afiliación a la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la afiliación a la caja de compensación: ' . $e->getMessage());
        }
    }

    public function update(CompensationFundAffiliation $compensationFundAffiliation, CompensationFundAffiliationId $compensationFundAffiliationId): CompensationFundAffiliation
    {
        try {
            $model = tap($this->compensationFundAffiliationModel->findOrFail($compensationFundAffiliationId->value()))
                ->update($this->mapToModelData($compensationFundAffiliation))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La afiliación a la caja de compensación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la afiliación a la caja de compensación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la afiliación a la caja de compensación: ' . $e->getMessage());
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

    private function mapToEntity($model): CompensationFundAffiliation
    {
        return new CompensationFundAffiliation(
            new CompensationFundAffiliationId($model->id),
            new CompensationFundAffiliationPersonId($model->person?->id ?? $model->person_id),
            new CompensationFundAffiliationCountryId($model->country?->id ?? $model->country_id),
            new CompensationFundAffiliationCompensationFundId($model->compensation_fund_id?->id ?? $model->compensation_fund_id),
            new CompensationFundAffiliationObservation($model->observation),
            new CompensationFundAffiliationStatus($model->status),
            new CompensationFundAffiliationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CompensationFundAffiliationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CompensationFundAffiliationRelationships($model->person, $model->country, $model->compensation_fund, $model->file)
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

    private function mapToModelData(CompensationFundAffiliation $compensationFundAffiliation): array
    {
        $array = [];

        if ($compensationFundAffiliation->personId()->value()) {
            $array['person_id'] = $compensationFundAffiliation->personId()->value();
        }

        if ($compensationFundAffiliation->countryId()->value()) {
            $array['country_id'] = $compensationFundAffiliation->countryId()->value();
        }

        if ($compensationFundAffiliation->compensationFundId()->value()) {
            $array['compensation_fund_id'] = $compensationFundAffiliation->compensationFundId()->value();
        }

        if ($compensationFundAffiliation->status()->value()) {
            $array['status'] = $compensationFundAffiliation->status()->value();
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
