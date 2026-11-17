<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Absence\Contracts\AbsenceContract;
use App\Models\Absence as AbsenceModel;
use App\Models\File as FileModel;
use App\Domain\Absence\Absence;
use App\Domain\Absence\File;
use App\Domain\Absence\Values\AbsenceAbsenceTypeId;
use App\Domain\Absence\Values\AbsenceCompensation;
use App\Domain\Absence\Values\AbsenceCreatedAt;
use App\Domain\Absence\Values\AbsenceId;
use App\Domain\Absence\Values\AbsenceEndDate;
use App\Domain\Absence\Values\AbsencePersonId;
use App\Domain\Absence\Values\AbsenceReason;
use App\Domain\Absence\Values\AbsenceRelationships;
use App\Domain\Absence\Values\AbsenceStartDate;
use App\Domain\Absence\Values\AbsenceStatus;
use App\Domain\Absence\Values\AbsenceUpdatedAt;
use App\Domain\Absence\Values\FileCreatedAt;
use App\Domain\Absence\Values\FileDeletedAt;
use App\Domain\Absence\Values\FileExtension;
use App\Domain\Absence\Values\FileFileSubtypeId;
use App\Domain\Absence\Values\FileFileTypeId;
use App\Domain\Absence\Values\FileId;
use App\Domain\Absence\Values\FileMetadata;
use App\Domain\Absence\Values\FileMime;
use App\Domain\Absence\Values\FileModelId;
use App\Domain\Absence\Values\FileModelType;
use App\Domain\Absence\Values\FileName;
use App\Domain\Absence\Values\FilePath;
use App\Domain\Absence\Values\FileSize;
use App\Domain\Absence\Values\FileUpdatedAt;
use App\Http\Requests\Absence\AbsenceAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AbsenceRepository implements AbsenceContract
{
    private $absenceModel;
    private $fileModel;

    public function __construct()
    {
        $this->absenceModel = new AbsenceModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, AbsenceAllRequest $request): LengthAwarePaginator
    {
        try {
            $absences = $this->absenceModel
                ->with([
                    'absence_type', 'file', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
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

            return $absences->paginate($perPage ?? $absences->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las solicitudes de permisos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las solicitudes de permisos: ' . $e->getMessage());
        }
    }

    public function find(AbsenceId $absenceId): ?Absence
    {
        try {
            $model = $this->absenceModel
                ->with([
                    'absence_type', 'file', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->findOrFail($absenceId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de permiso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la solicitud de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la solicitud de permiso: ' . $e->getMessage());
        }
    }

    public function store(Absence $absence): Absence
    {
        try {
            $model = $this->absenceModel
                ->create($this->mapToModelData($absence));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la solicitud de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la solicitud de permiso: ' . $e->getMessage());
        }
    }

    public function update(Absence $absence, AbsenceId $absenceId): Absence
    {
        try {
            $model = tap($this->absenceModel->findOrFail($absenceId->value()))
                ->update($this->mapToModelData($absence))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de permiso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la solicitud de permiso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la solicitud de permiso: ' . $e->getMessage());
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

    private function mapToEntity($model): Absence
    {
        return new Absence(
            new AbsenceId($model->id),
            new AbsencePersonId($model->person?->id ?? $model->person_id),
            new AbsenceAbsenceTypeId($model->absence_type?->id ?? $model->absence_type_id),
            new AbsenceStartDate($model->start_date?->format('Y-m-d H:i:s')),
            new AbsenceEndDate($model->end_date?->format('Y-m-d H:i:s')),
            new AbsenceReason($model->reason),
            new AbsenceCompensation($model->compensation),
            new AbsenceStatus($model->status),
            new AbsenceCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AbsenceUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AbsenceRelationships($model->person, $model->absence_type, $model->file)
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

    private function mapToModelData(Absence $absence): array
    {
        $array = [];

        if ($absence->personId()->value()) {
            $array['person_id'] = $absence->personId()->value();
        }

        if ($absence->absenceTypeId()->value()) {
            $array['absence_type_id'] = $absence->absenceTypeId()->value();
        }

        if ($absence->startDate()->value()) {
            $array['start_date'] = $absence->startDate()->value();
        }

        if ($absence->endDate()->value()) {
            $array['end_date'] = $absence->endDate()->value();
        }

        if ($absence->reason()->value()) {
            $array['reason'] = $absence->reason()->value();
        }

        if ($absence->compensation()->value()) {
            $array['compensation'] = $absence->compensation()->value();
        }

        if ($absence->status()->value()) {
            $array['status'] = $absence->status()->value();
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
