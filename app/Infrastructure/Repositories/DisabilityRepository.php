<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Disability\Contracts\DisabilityContract;
use App\Models\Disability as DisabilityModel;
use App\Models\File as FileModel;
use App\Domain\Disability\Disability;
use App\Domain\Disability\File;
use App\Domain\Disability\Values\DisabilityCIECodeId;
use App\Domain\Disability\Values\DisabilityDisabilityTypeId;
use App\Domain\Disability\Values\DisabilityCreatedAt;
use App\Domain\Disability\Values\DisabilityId;
use App\Domain\Disability\Values\DisabilityEndDate;
use App\Domain\Disability\Values\DisabilityPersonId;
use App\Domain\Disability\Values\DisabilityReason;
use App\Domain\Disability\Values\DisabilityRelationships;
use App\Domain\Disability\Values\DisabilityStartDate;
use App\Domain\Disability\Values\DisabilityStatus;
use App\Domain\Disability\Values\DisabilityUpdatedAt;
use App\Domain\Disability\Values\FileCreatedAt;
use App\Domain\Disability\Values\FileDeletedAt;
use App\Domain\Disability\Values\FileExtension;
use App\Domain\Disability\Values\FileFileSubtypeId;
use App\Domain\Disability\Values\FileFileTypeId;
use App\Domain\Disability\Values\FileId;
use App\Domain\Disability\Values\FileMetadata;
use App\Domain\Disability\Values\FileMime;
use App\Domain\Disability\Values\FileModelId;
use App\Domain\Disability\Values\FileModelType;
use App\Domain\Disability\Values\FileName;
use App\Domain\Disability\Values\FilePath;
use App\Domain\Disability\Values\FileSize;
use App\Domain\Disability\Values\FileUpdatedAt;
use App\Http\Requests\Disability\DisabilityAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class DisabilityRepository implements DisabilityContract
{
    private $disabilityModel;
    private $fileModel;

    public function __construct()
    {
        $this->disabilityModel = new DisabilityModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, DisabilityAllRequest $request): LengthAwarePaginator
    {
        try {
            $disabilities = $this->disabilityModel
                ->with([
                    'disability_type', 'cie_code.cie_subgroup.cie_group.cie_chapter', 'file', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
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

            return $disabilities->paginate($perPage ?? $disabilities->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los reportes de incapacidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los reportes de incapacidades: ' . $e->getMessage());
        }
    }

    public function find(DisabilityId $disabilityId): ?Disability
    {
        try {
            $model = $this->disabilityModel
                ->with([
                    'disability_type', 'cie_code.cie_subgroup.cie_group.cie_chapter', 'file', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->findOrFail($disabilityId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El reporte de incapacidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el reporte de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el reporte de incapacidad: ' . $e->getMessage());
        }
    }

    public function store(Disability $disability): Disability
    {
        try {
            $model = $this->disabilityModel
                ->create($this->mapToModelData($disability));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el reporte de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el reporte de incapacidad: ' . $e->getMessage());
        }
    }

    public function update(Disability $disability, DisabilityId $disabilityId): Disability
    {
        try {
            $model = tap($this->disabilityModel->findOrFail($disabilityId->value()))
                ->update($this->mapToModelData($disability))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El reporte de incapacidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el reporte de incapacidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el reporte de incapacidad: ' . $e->getMessage());
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

    private function mapToEntity($model): Disability
    {
        return new Disability(
            new DisabilityId($model->id),
            new DisabilityPersonId($model->person?->id ?? $model->person_id),
            new DisabilityDisabilityTypeId($model->disability_type?->id ?? $model->disability_type_id),
            new DisabilityCIECodeId($model->cie_code?->id ?? $model->cie_code_id),
            new DisabilityStartDate($model->start_date?->format('d/m/Y H:i:s')),
            new DisabilityEndDate($model->end_date?->format('d/m/Y H:i:s')),
            new DisabilityReason($model->reason),
            new DisabilityStatus($model->status),
            new DisabilityCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new DisabilityUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new DisabilityRelationships($model->person, $model->disability_type, $model->cie_code, $model->file)
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

    private function mapToModelData(Disability $disability): array
    {
        $array = [];

        if ($disability->personId()->value()) {
            $array['person_id'] = $disability->personId()->value();
        }

        if ($disability->disabilityTypeId()->value()) {
            $array['disability_type_id'] = $disability->disabilityTypeId()->value();
        }

        if ($disability->cieCodeId()->value()) {
            $array['cie_code_id'] = $disability->cieCodeId()->value();
        }

        if ($disability->startDate()->value()) {
            $array['start_date'] = $disability->startDate()->value();
        }

        if ($disability->endDate()->value()) {
            $array['end_date'] = $disability->endDate()->value();
        }

        if ($disability->reason()->value()) {
            $array['reason'] = $disability->reason()->value();
        }

        if ($disability->status()->value()) {
            $array['status'] = $disability->status()->value();
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
