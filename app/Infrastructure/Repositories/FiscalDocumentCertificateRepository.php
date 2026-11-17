<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\FiscalDocumentCertificate\Contracts\FiscalDocumentCertificateContract;
use App\Domain\FiscalDocumentCertificate\File;
use App\Models\FiscalDocumentCertificate as FiscalDocumentCertificateModel;
use App\Models\File as FileModel;
use App\Domain\FiscalDocumentCertificate\FiscalDocumentCertificate;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateAccountNumber;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateFiscalDocumentAccountTypeId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateFiscalDocumentId;
use App\Domain\FiscalDocumentCertificate\Values\FileCreatedAt;
use App\Domain\FiscalDocumentCertificate\Values\FileDeletedAt;
use App\Domain\FiscalDocumentCertificate\Values\FileExtension;
use App\Domain\FiscalDocumentCertificate\Values\FileFileSubtypeId;
use App\Domain\FiscalDocumentCertificate\Values\FileFileTypeId;
use App\Domain\FiscalDocumentCertificate\Values\FileId;
use App\Domain\FiscalDocumentCertificate\Values\FileMetadata;
use App\Domain\FiscalDocumentCertificate\Values\FileMime;
use App\Domain\FiscalDocumentCertificate\Values\FileModelId;
use App\Domain\FiscalDocumentCertificate\Values\FileModelType;
use App\Domain\FiscalDocumentCertificate\Values\FileName;
use App\Domain\FiscalDocumentCertificate\Values\FilePath;
use App\Domain\FiscalDocumentCertificate\Values\FileSize;
use App\Domain\FiscalDocumentCertificate\Values\FileUpdatedAt;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateCountryId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateCreatedAt;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateObservation;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificatePersonId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateRelationships;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateStatus;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateUpdatedAt;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FiscalDocumentCertificateRepository implements FiscalDocumentCertificateContract
{
    private $fiscalDocumentCertificateModel;
    private $fileModel;

    public function __construct()
    {
        $this->fiscalDocumentCertificateModel = new FiscalDocumentCertificateModel;
        $this->fileModel = new FileModel;
    }

    public function find(FiscalDocumentCertificateFindRequest $request, FiscalDocumentCertificateId $fiscalDocumentCertificateId): ?FiscalDocumentCertificate
    {
        try {
            $model = $this->fiscalDocumentCertificateModel
                ->with(['person', 'country', 'fiscal_document', 'file'])
                ->when($fiscalDocumentCertificateId->value(), function ($query) use ($fiscalDocumentCertificateId) {
                    $query->where('id', $fiscalDocumentCertificateId->value());
                })
                ->when(!$fiscalDocumentCertificateId->value(), function ($query) use ($request) {
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
            throw new HttpException(404, 'La certificación documento fiscal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la certificación documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la certificación documento fiscal: ' . $e->getMessage());
        }
    }

    public function store(FiscalDocumentCertificate $fiscalDocumentCertificate): FiscalDocumentCertificate
    {
        try {
            $model = $this->fiscalDocumentCertificateModel
                ->create($this->mapToModelData($fiscalDocumentCertificate));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la certificación documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la certificación documento fiscal: ' . $e->getMessage());
        }
    }

    public function update(FiscalDocumentCertificate $fiscalDocumentCertificate, FiscalDocumentCertificateId $fiscalDocumentCertificateId): FiscalDocumentCertificate
    {
        try {
            $model = tap($this->fiscalDocumentCertificateModel->findOrFail($fiscalDocumentCertificateId->value()))
                ->update($this->mapToModelData($fiscalDocumentCertificate))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La certificación documento fiscal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la certificación documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la certificación documento fiscal: ' . $e->getMessage());
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

    private function mapToEntity($model): FiscalDocumentCertificate
    {
        return new FiscalDocumentCertificate(
            new FiscalDocumentCertificateId($model->id),
            new FiscalDocumentCertificatePersonId($model->person?->id ?? $model->person_id),
            new FiscalDocumentCertificateCountryId($model->country?->id ?? $model->country_id),
            new FiscalDocumentCertificateFiscalDocumentId($model->fiscal_document_id?->id ?? $model->fiscal_document_id),
            new FiscalDocumentCertificateObservation($model->observation),
            new FiscalDocumentCertificateStatus($model->status),
            new FiscalDocumentCertificateCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FiscalDocumentCertificateUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FiscalDocumentCertificateRelationships($model->person, $model->country, $model->fiscal_document, $model->file)
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

    private function mapToModelData(FiscalDocumentCertificate $fiscalDocumentCertificate): array
    {
        $array = [];

        if ($fiscalDocumentCertificate->personId()->value()) {
            $array['person_id'] = $fiscalDocumentCertificate->personId()->value();
        }

        if ($fiscalDocumentCertificate->countryId()->value()) {
            $array['country_id'] = $fiscalDocumentCertificate->countryId()->value();
        }

        if ($fiscalDocumentCertificate->fiscalDocumentId()->value()) {
            $array['fiscal_document_id'] = $fiscalDocumentCertificate->fiscalDocumentId()->value();
        }

        if ($fiscalDocumentCertificate->status()->value()) {
            $array['status'] = $fiscalDocumentCertificate->status()->value();
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
