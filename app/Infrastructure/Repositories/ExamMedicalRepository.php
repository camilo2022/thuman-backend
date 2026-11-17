<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\ExamMedical\Contracts\ExamMedicalContract;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalApplicationId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalCreatedAt;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalRelationships;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalStatus;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalUpdatedAt;
use App\Domain\Vacant\Application\ExamMedical\ExamMedical;
use App\Domain\Vacant\Application\ExamMedical\File;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalAddress;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalCityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDateTime;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDescription;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalEpidemiologicalProgramId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalHealthEntityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalIndication;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalNeighborhood;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalResult;
use App\Domain\Vacant\Application\ExamMedical\Values\FileCreatedAt;
use App\Domain\Vacant\Application\ExamMedical\Values\FileDeletedAt;
use App\Domain\Vacant\Application\ExamMedical\Values\FileExtension;
use App\Domain\Vacant\Application\ExamMedical\Values\FileFileSubtypeId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileFileTypeId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileMetadata;
use App\Domain\Vacant\Application\ExamMedical\Values\FileMime;
use App\Domain\Vacant\Application\ExamMedical\Values\FileModelId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileModelType;
use App\Domain\Vacant\Application\ExamMedical\Values\FileName;
use App\Domain\Vacant\Application\ExamMedical\Values\FilePath;
use App\Domain\Vacant\Application\ExamMedical\Values\FileSize;
use App\Domain\Vacant\Application\ExamMedical\Values\FileUpdatedAt;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalAllRequest;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalFindRequest;
use App\Models\Application as ApplicationModel;
use App\Models\ExamMedical as ExamMedicalModel;
use App\Models\File as FileModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ExamMedicalRepository implements ExamMedicalContract
{
    private $examMedicalModel;
    private $fileModel;

    public function __construct()
    {
        $this->examMedicalModel = new ExamMedicalModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, ExamMedicalAllRequest $request): LengthAwarePaginator
    {
        try {
            $examMedicals = $this->examMedicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'health_entity', 'file',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'city.departament.country', 'epidemiological_program'
                ])
                ->whereHas('application', fn($r) => $r->whereIn('status', [ApplicationModel::FINALIST]))
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->orderBy('id', 'asc');

            return $examMedicals->paginate($perPage ?? $examMedicals->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los registros de los examenes médicos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los registros de los examenes médicos: ' . $e->getMessage());
        }
    }

    public function find(ExamMedicalFindRequest $request, ExamMedicalId $examMedicalId): ?ExamMedical
    {
        try {
            $model = $this->examMedicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'health_entity', 'file',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'city.departament.country', 'epidemiological_program'
                ])
                ->when($examMedicalId->value(), function ($query) use ($examMedicalId) {
                    $query->where('id', $examMedicalId->value());
                })
                ->when(!$examMedicalId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de examen médico no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro de examen médico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro de examen médico: ' . $e->getMessage());
        }
    }

    public function store(ExamMedical $examMedical): ExamMedical
    {
        try {
            $model = $this->examMedicalModel
                ->create($this->mapToModelData($examMedical));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro de examen médico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro de examen médico: ' . $e->getMessage());
        }
    }

    public function update(ExamMedical $vacant, ExamMedicalId $vacantId): ExamMedical
    {
        try {
            $model = tap($this->examMedicalModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de examen médico no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro de examen médico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro de examen médico: ' . $e->getMessage());
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

    private function mapToEntity($model): ExamMedical
    {
        return new ExamMedical(
            new ExamMedicalId($model->id),
            new ExamMedicalApplicationId($model->application?->id ?? $model->application_id),
            new ExamMedicalDateTime($model->date_time?->format('Y-m-d H:i:s')),
            new ExamMedicalCityId($model->city_id),
            new ExamMedicalHealthEntityId($model->health_entity_id),
            new ExamMedicalAddress($model->address),
            new ExamMedicalNeighborhood($model->neighborhood),
            new ExamMedicalResult($model->result),
            new ExamMedicalEpidemiologicalProgramId($model->epidemiological_program_id),
            new ExamMedicalDescription($model->description),
            new ExamMedicalIndication($model->indication),
            new ExamMedicalStatus($model->status),
            new ExamMedicalCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ExamMedicalUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ExamMedicalRelationships($model->application, $model->city, $model->health_entity, $model->epidemiological_program, $model->file)
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

    private function mapToModelData(ExamMedical $examMedical): array
    {
        $array = [];

        if ($examMedical->applicationId()->value()) {
            $array['application_id'] = $examMedical->applicationId()->value();
        }

        if ($examMedical->dateTime()->value()) {
            $array['date_time'] = $examMedical->dateTime()->value();
        }

        if ($examMedical->cityId()->value()) {
            $array['city_id'] = $examMedical->cityId()->value();
        }

        if ($examMedical->healthEntityId()->value()) {
            $array['health_entity_id'] = $examMedical->healthEntityId()->value();
        }

        if ($examMedical->address()->value()) {
            $array['address'] = $examMedical->address()->value();
        }

        if ($examMedical->neighborhood()->value()) {
            $array['neighborhood'] = $examMedical->neighborhood()->value();
        }

        if ($examMedical->result()->value()) {
            $array['result'] = $examMedical->result()->value();
        }

        if ($examMedical->epidemiologicalProgramId()->value()) {
            $array['epidemiological_program_id'] = $examMedical->epidemiologicalProgramId()->value();
        }

        if ($examMedical->description()->value()) {
            $array['description'] = $examMedical->description()->value();
        }

        if ($examMedical->indication()->value()) {
            $array['indication'] = $examMedical->indication()->value();
        }

        if ($examMedical->status()->value()) {
            $array['status'] = $examMedical->status()->value();
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
