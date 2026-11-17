<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\BankCertificate\Contracts\BankCertificateContract;
use App\Domain\BankCertificate\File;
use App\Models\BankCertificate as BankCertificateModel;
use App\Models\File as FileModel;
use App\Domain\BankCertificate\BankCertificate;
use App\Domain\BankCertificate\Values\BankCertificateAccountNumber;
use App\Domain\BankCertificate\Values\BankCertificateBankAccountTypeId;
use App\Domain\BankCertificate\Values\BankCertificateBankId;
use App\Domain\BankCertificate\Values\FileCreatedAt;
use App\Domain\BankCertificate\Values\FileDeletedAt;
use App\Domain\BankCertificate\Values\FileExtension;
use App\Domain\BankCertificate\Values\FileFileSubtypeId;
use App\Domain\BankCertificate\Values\FileFileTypeId;
use App\Domain\BankCertificate\Values\FileId;
use App\Domain\BankCertificate\Values\FileMetadata;
use App\Domain\BankCertificate\Values\FileMime;
use App\Domain\BankCertificate\Values\FileModelId;
use App\Domain\BankCertificate\Values\FileModelType;
use App\Domain\BankCertificate\Values\FileName;
use App\Domain\BankCertificate\Values\FilePath;
use App\Domain\BankCertificate\Values\FileSize;
use App\Domain\BankCertificate\Values\FileUpdatedAt;
use App\Domain\BankCertificate\Values\BankCertificateCountryId;
use App\Domain\BankCertificate\Values\BankCertificateCreatedAt;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Domain\BankCertificate\Values\BankCertificateObservation;
use App\Domain\BankCertificate\Values\BankCertificatePersonId;
use App\Domain\BankCertificate\Values\BankCertificateRelationships;
use App\Domain\BankCertificate\Values\BankCertificateStatus;
use App\Domain\BankCertificate\Values\BankCertificateUpdatedAt;
use App\Http\Requests\BankCertificate\BankCertificateFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class BankCertificateRepository implements BankCertificateContract
{
    private $bankCertificateModel;
    private $fileModel;

    public function __construct()
    {
        $this->bankCertificateModel = new BankCertificateModel;
        $this->fileModel = new FileModel;
    }

    public function find(BankCertificateFindRequest $request, BankCertificateId $bankCertificateId): ?BankCertificate
    {
        try {
            $model = $this->bankCertificateModel
                ->with(['person', 'country', 'bank', 'bank_account_type', 'file'])
                ->when($bankCertificateId->value(), function ($query) use ($bankCertificateId) {
                    $query->where('id', $bankCertificateId->value());
                })
                ->when(!$bankCertificateId->value(), function ($query) use ($request) {
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
            throw new HttpException(404, 'La certificación bancaria no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la certificación bancaria: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la certificación bancaria: ' . $e->getMessage());
        }
    }

    public function store(BankCertificate $bankCertificate): BankCertificate
    {
        try {
            $model = $this->bankCertificateModel
                ->create($this->mapToModelData($bankCertificate));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la certificación bancaria: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la certificación bancaria: ' . $e->getMessage());
        }
    }

    public function update(BankCertificate $bankCertificate, BankCertificateId $bankCertificateId): BankCertificate
    {
        try {
            $model = tap($this->bankCertificateModel->findOrFail($bankCertificateId->value()))
                ->update($this->mapToModelData($bankCertificate))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La certificación bancaria no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la certificación bancaria: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la certificación bancaria: ' . $e->getMessage());
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

    private function mapToEntity($model): BankCertificate
    {
        return new BankCertificate(
            new BankCertificateId($model->id),
            new BankCertificatePersonId($model->person?->id ?? $model->person_id),
            new BankCertificateCountryId($model->country?->id ?? $model->country_id),
            new BankCertificateBankId($model->bank_id?->id ?? $model->bank_id),
            new BankCertificateBankAccountTypeId($model->bank_account_type_id?->id ?? $model->bank_account_type_id),
            new BankCertificateAccountNumber($model->account_number),
            new BankCertificateObservation($model->observation),
            new BankCertificateStatus($model->status),
            new BankCertificateCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new BankCertificateUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new BankCertificateRelationships($model->person, $model->country, $model->bank, $model->bank_account_type, $model->file)
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

    private function mapToModelData(BankCertificate $bankCertificate): array
    {
        $array = [];

        if ($bankCertificate->personId()->value()) {
            $array['person_id'] = $bankCertificate->personId()->value();
        }

        if ($bankCertificate->countryId()->value()) {
            $array['country_id'] = $bankCertificate->countryId()->value();
        }

        if ($bankCertificate->bankId()->value()) {
            $array['bank_id'] = $bankCertificate->bankId()->value();
        }

        if ($bankCertificate->bankAccountTypeId()->value()) {
            $array['bank_account_type_id'] = $bankCertificate->bankAccountTypeId()->value();
        }

        if ($bankCertificate->accountNumber()->value()) {
            $array['account_number'] = $bankCertificate->accountNumber()->value();
        }

        if ($bankCertificate->observation()->value()) {
            $array['observation'] = $bankCertificate->observation()->value();
        }

        if ($bankCertificate->status()->value()) {
            $array['status'] = $bankCertificate->status()->value();
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
