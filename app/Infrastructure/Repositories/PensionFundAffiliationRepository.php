<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\PensionFundAffiliation\Contracts\PensionFundAffiliationContract;
use App\Domain\PensionFundAffiliation\File;
use App\Models\PensionFundAffiliation as PensionFundAffiliationModel;
use App\Models\File as FileModel;
use App\Domain\PensionFundAffiliation\PensionFundAffiliation;
use App\Domain\PensionFundAffiliation\Values\FileCreatedAt;
use App\Domain\PensionFundAffiliation\Values\FileDeletedAt;
use App\Domain\PensionFundAffiliation\Values\FileExtension;
use App\Domain\PensionFundAffiliation\Values\FileFileSubtypeId;
use App\Domain\PensionFundAffiliation\Values\FileFileTypeId;
use App\Domain\PensionFundAffiliation\Values\FileId;
use App\Domain\PensionFundAffiliation\Values\FileMetadata;
use App\Domain\PensionFundAffiliation\Values\FileMime;
use App\Domain\PensionFundAffiliation\Values\FileModelId;
use App\Domain\PensionFundAffiliation\Values\FileModelType;
use App\Domain\PensionFundAffiliation\Values\FileName;
use App\Domain\PensionFundAffiliation\Values\FilePath;
use App\Domain\PensionFundAffiliation\Values\FileSize;
use App\Domain\PensionFundAffiliation\Values\FileUpdatedAt;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationCountryId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationCreatedAt;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationObservation;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPersonId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPensionFundId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationRelationships;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationStatus;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationUpdatedAt;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PensionFundAffiliationRepository implements PensionFundAffiliationContract
{
    private $pensionFundAffiliationModel;
    private $fileModel;

    public function __construct()
    {
        $this->pensionFundAffiliationModel = new PensionFundAffiliationModel;
        $this->fileModel = new FileModel;
    }

    public function find(PensionFundAffiliationFindRequest $request, PensionFundAffiliationId $pensionFundAffiliationId): ?PensionFundAffiliation
    {
        try {
            $model = $this->pensionFundAffiliationModel
                ->with(['person', 'country', 'pension_fund', 'file'])
                ->when($pensionFundAffiliationId->value(), function ($query) use ($pensionFundAffiliationId) {
                    $query->where('id', $pensionFundAffiliationId->value());
                })
                ->when(!$pensionFundAffiliationId->value(), function ($query) use ($request) {
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
            throw new HttpException(404, 'La afiliación al fondo de pensión no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la afiliación al fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la afiliación al fondo de pensión: ' . $e->getMessage());
        }
    }

    public function store(PensionFundAffiliation $pensionFundAffiliation): PensionFundAffiliation
    {
        try {
            $model = $this->pensionFundAffiliationModel
                ->create($this->mapToModelData($pensionFundAffiliation));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la afiliación al fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la afiliación al fondo de pensión: ' . $e->getMessage());
        }
    }

    public function update(PensionFundAffiliation $pensionFundAffiliation, PensionFundAffiliationId $pensionFundAffiliationId): PensionFundAffiliation
    {
        try {
            $model = tap($this->pensionFundAffiliationModel->findOrFail($pensionFundAffiliationId->value()))
                ->update($this->mapToModelData($pensionFundAffiliation))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La afiliación al fondo de pensión no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la afiliación al fondo de pensión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la afiliación al fondo de pensión: ' . $e->getMessage());
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

    private function mapToEntity($model): PensionFundAffiliation
    {
        return new PensionFundAffiliation(
            new PensionFundAffiliationId($model->id),
            new PensionFundAffiliationPersonId($model->person?->id ?? $model->person_id),
            new PensionFundAffiliationCountryId($model->country?->id ?? $model->country_id),
            new PensionFundAffiliationPensionFundId($model->pension_fund_id?->id ?? $model->pension_fund_id),
            new PensionFundAffiliationObservation($model->observation),
            new PensionFundAffiliationStatus($model->status),
            new PensionFundAffiliationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PensionFundAffiliationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PensionFundAffiliationRelationships($model->person, $model->country, $model->pension_fund, $model->file)
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

    private function mapToModelData(PensionFundAffiliation $pensionFundAffiliation): array
    {
        $array = [];

        if ($pensionFundAffiliation->personId()->value()) {
            $array['person_id'] = $pensionFundAffiliation->personId()->value();
        }

        if ($pensionFundAffiliation->countryId()->value()) {
            $array['country_id'] = $pensionFundAffiliation->countryId()->value();
        }

        if ($pensionFundAffiliation->pensionFundId()->value()) {
            $array['pension_fund_id'] = $pensionFundAffiliation->pensionFundId()->value();
        }

        if ($pensionFundAffiliation->observation()->value()) {
            $array['observation'] = $pensionFundAffiliation->observation()->value();
        }

        if ($pensionFundAffiliation->status()->value()) {
            $array['status'] = $pensionFundAffiliation->status()->value();
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
