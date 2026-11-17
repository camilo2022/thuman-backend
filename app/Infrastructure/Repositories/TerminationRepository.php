<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Termination\Contracts\TerminationContract;
use App\Domain\Termination\File;
use App\Models\File as FileModel;
use App\Models\Termination as TerminationModel;
use App\Models\TerminationType as TerminationTypeModel;
use App\Models\Reason as ReasonModel;
use App\Domain\Termination\Termination;
use App\Domain\Termination\Values\FileCreatedAt;
use App\Domain\Termination\Values\FileDeletedAt;
use App\Domain\Termination\Values\FileExtension;
use App\Domain\Termination\Values\FileFileSubtypeId;
use App\Domain\Termination\Values\FileFileTypeId;
use App\Domain\Termination\Values\FileId;
use App\Domain\Termination\Values\FileMetadata;
use App\Domain\Termination\Values\FileMime;
use App\Domain\Termination\Values\FileModelId;
use App\Domain\Termination\Values\FileModelType;
use App\Domain\Termination\Values\FileName;
use App\Domain\Termination\Values\FilePath;
use App\Domain\Termination\Values\FileSize;
use App\Domain\Termination\Values\FileUpdatedAt;
use App\Domain\Termination\Values\TerminationCreatedAt;
use App\Domain\Termination\Values\TerminationDate;
use App\Domain\Termination\Values\TerminationDecision;
use App\Domain\Termination\Values\TerminationId;
use App\Domain\Termination\Values\TerminationModelId;
use App\Domain\Termination\Values\TerminationModelType;
use App\Domain\Termination\Values\TerminationPersonId;
use App\Domain\Termination\Values\TerminationReason;
use App\Domain\Termination\Values\TerminationRelationships;
use App\Domain\Termination\Values\TerminationStatus;
use App\Domain\Termination\Values\TerminationUpdatedAt;
use App\Http\Requests\Termination\TerminationAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class TerminationRepository implements TerminationContract
{
    private $terminationModel;
    private $fileModel;

    public function __construct()
    {
        $this->terminationModel = new TerminationModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, TerminationAllRequest $request): LengthAwarePaginator
    {
        try {
            $terminations = $this->terminationModel
                ->with([
                    'model' => fn(MorphTo $morphTo) => $morphTo->morphWith([TerminationTypeModel::class => [], ReasonModel::class => ['termination_type']]),
                    'file',
                    'person.employee.position.area',
                    'person.contract.contract_type',
                    'person.birth_city.departament.country',
                    'person.document_issue_city.departament.country',
                    'person.city.departament.country',
                    'person.dialing_country',
                    'person.document_type',
                    'person.sex',
                    'person.gender',
                    'person.blood_type',
                    'person.marital_status',
                    'person.file.file_type',
                    'person.file.file_subtype',
                    'person.photo.file_type',
                    'person.photo.file_subtype',
                    'person.signature.file_type',
                    'person.signature.file_subtype'
                ])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('person_id'), function ($q) use ($request) {
                    $q->where('person_id', $request->integer('person_id'));
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->when($request->filled('area_id'), function ($q) use ($request) {
                    $q->whereHas('person.contract.position', function ($q) use ($request) {
                        $q->where('area_id', $request->input('area_id'));
                    });
                })
                ->when($request->filled('entity_id'), function ($q) use ($request) {
                    $q->whereHas('person.contract.headquarter', function ($q) use ($request) {
                        $q->where('entity_id', $request->input('entity_id'));
                    });
                })
                ->when($request->filled('contract_type_id'), function ($q) use ($request) {
                    $q->whereHas('person.contract', function ($q) use ($request) {
                        $q->where('contract_type_id', $request->input('contract_type_id'));
                    });
                })
                ->orderBy('id', 'asc');

            return $terminations->paginate($perPage ?? $terminations->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las terminaciones de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las terminaciones de contrato: ' . $e->getMessage());
        }
    }

    public function find(TerminationId $terminationId): ?Termination
    {
        try {
            $model = $this->terminationModel
                ->with([
                    'model' => fn(MorphTo $morphTo) => $morphTo->morphWith([TerminationTypeModel::class => [], ReasonModel::class => ['termination_type']]),
                    'file',
                    'person.employee.position.area',
                    'person.contract.contract_type',
                    'person.birth_city.departament.country',
                    'person.document_issue_city.departament.country',
                    'person.city.departament.country',
                    'person.dialing_country',
                    'person.document_type',
                    'person.sex',
                    'person.gender',
                    'person.blood_type',
                    'person.marital_status',
                    'person.file.file_type',
                    'person.file.file_subtype',
                    'person.photo.file_type',
                    'person.photo.file_subtype',
                    'person.signature.file_type',
                    'person.signature.file_subtype'
                ])
                ->findOrFail($terminationId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La terminacion de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la terminacion de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la terminacion de contrato: ' . $e->getMessage());
        }
    }

    public function store(Termination $termination): Termination
    {
        try {
            $model = $this->terminationModel
                ->create($this->mapToModelData($termination));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la terminacion de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la terminacion de contrato: ' . $e->getMessage());
        }
    }

    public function update(Termination $termination, TerminationId $terminationId): Termination
    {
        try {
            $model = tap($this->terminationModel->findOrFail($terminationId->value()))
                ->update($this->mapToModelData($termination))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La terminacion de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la terminacion de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la terminacion de contrato: ' . $e->getMessage());
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

    private function mapToEntity($model): Termination
    {
        $relatedModel = $model->model;

        if ($model->model_type === TerminationTypeModel::class && $relatedModel) {
            $relatedModel->setAttribute('termination_type', [
                $relatedModel->only([
                    'id',
                    'item_id',
                    'name',
                    'description',
                    'settings',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ])
            ]);
        }

        return new Termination(
            new TerminationId($model->id),
            new TerminationPersonId($model->person?->id ?? $model->person_id),
            new TerminationModelId($relatedModel?->id ?? $model->model_id),
            new TerminationModelType($model->model_type),
            new TerminationReason($model->reason),
            new TerminationDecision($model->decision),
            new TerminationDate($model->date),
            new TerminationStatus($model->status),
            new TerminationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new TerminationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new TerminationRelationships(
                $model->person,
                $relatedModel,
                $model->file
            )
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

    private function mapToModelData(Termination $termination): array
    {
        $array = [];

        if ($termination->personId()->value()) {
            $array['person_id'] = $termination->personId()->value();
        }

        if ($termination->modelId()->value()) {
            $array['model_id'] = $termination->modelId()->value();
        }

        if ($termination->modelType()->value()) {
            $array['model_type'] = $termination->modelType()->value();
        }

        if ($termination->reason()->value()) {
            $array['reason'] = $termination->reason()->value();
        }

        if ($termination->decision()->value()) {
            $array['decision'] = $termination->decision()->value();
        }

        if ($termination->status()->value()) {
            $array['status'] = $termination->status()->value();
        }

        if ($termination->date()->value()) {
            $array['date'] = $termination->date()->value();
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
