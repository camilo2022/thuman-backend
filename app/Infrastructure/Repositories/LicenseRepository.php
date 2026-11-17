<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\License\Contracts\LicenseContract;
use App\Models\License as LicenseModel;
use App\Models\File as FileModel;
use App\Domain\License\License;
use App\Domain\License\File;
use App\Domain\License\Values\LicenseLicenseTypeId;
use App\Domain\License\Values\LicenseCreatedAt;
use App\Domain\License\Values\LicenseId;
use App\Domain\License\Values\LicenseEndDate;
use App\Domain\License\Values\LicensePersonId;
use App\Domain\License\Values\LicenseReason;
use App\Domain\License\Values\LicenseRelationships;
use App\Domain\License\Values\LicenseStartDate;
use App\Domain\License\Values\LicenseStatus;
use App\Domain\License\Values\LicenseUpdatedAt;
use App\Domain\License\Values\FileCreatedAt;
use App\Domain\License\Values\FileDeletedAt;
use App\Domain\License\Values\FileExtension;
use App\Domain\License\Values\FileFileSubtypeId;
use App\Domain\License\Values\FileFileTypeId;
use App\Domain\License\Values\FileId;
use App\Domain\License\Values\FileMetadata;
use App\Domain\License\Values\FileMime;
use App\Domain\License\Values\FileModelId;
use App\Domain\License\Values\FileModelType;
use App\Domain\License\Values\FileName;
use App\Domain\License\Values\FilePath;
use App\Domain\License\Values\FileSize;
use App\Domain\License\Values\FileUpdatedAt;
use App\Http\Requests\License\LicenseAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LicenseRepository implements LicenseContract
{
    private $licenseModel;
    private $fileModel;

    public function __construct()
    {
        $this->licenseModel = new LicenseModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, LicenseAllRequest $request): LengthAwarePaginator
    {
        try {
            $licenses = $this->licenseModel
                ->with([
                    'license_type', 'file', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('person_id'), function ($q) use ($request) {
                    $q->where('person_id', $request->integer('person_id'));
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->when($request->filled('area_id'), function ($q) use ($request) {
                    $q->whereHas('person.employee.position', function ($sq) use ($request) {
                        $sq->where('area_id', $request->integer('area_id'));
                    });
                })
                ->orderBy('id', 'asc');

            return $licenses->paginate($perPage ?? $licenses->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las solicitudes de licencias: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las solicitudes de licencias: ' . $e->getMessage());
        }
    }

    public function find(LicenseId $licenseId): ?License
    {
        try {
            $model = $this->licenseModel
                ->with([
                    'license_type', 'file', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->findOrFail($licenseId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de licencia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la solicitud de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la solicitud de licencia: ' . $e->getMessage());
        }
    }

    public function store(License $license): License
    {
        try {
            $model = $this->licenseModel
                ->create($this->mapToModelData($license));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la solicitud de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la solicitud de licencia: ' . $e->getMessage());
        }
    }

    public function update(License $license, LicenseId $licenseId): License
    {
        try {
            $model = tap($this->licenseModel->findOrFail($licenseId->value()))
                ->update($this->mapToModelData($license))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de licencia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la solicitud de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la solicitud de licencia: ' . $e->getMessage());
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

    private function mapToEntity($model): License
    {
        return new License(
            new LicenseId($model->id),
            new LicensePersonId($model->person?->id ?? $model->person_id),
            new LicenseLicenseTypeId($model->license_type?->id ?? $model->license_type_id),
            new LicenseStartDate($model->start_date?->format('d/m/Y')),
            new LicenseEndDate($model->end_date?->format('d/m/Y')),
            new LicenseReason($model->reason),
            new LicenseStatus($model->status),
            new LicenseCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new LicenseUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new LicenseRelationships($model->person, $model->license_type, $model->file)
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

    private function mapToModelData(License $license): array
    {
        $array = [];

        if ($license->personId()->value()) {
            $array['person_id'] = $license->personId()->value();
        }

        if ($license->licenseTypeId()->value()) {
            $array['license_type_id'] = $license->licenseTypeId()->value();
        }

        if ($license->startDate()->value()) {
            $array['start_date'] = $license->startDate()->value();
        }

        if ($license->endDate()->value()) {
            $array['end_date'] = $license->endDate()->value();
        }

        if ($license->reason()->value()) {
            $array['reason'] = $license->reason()->value();
        }

        if ($license->status()->value()) {
            $array['status'] = $license->status()->value();
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
            $array['keys']['file_subtype_id'] = $file->fileSubtypeId()->value();
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
