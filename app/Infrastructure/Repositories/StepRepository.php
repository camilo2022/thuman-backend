<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Induction\Step\Contracts\StepContract;
use App\Domain\Induction\Step\File;
use App\Models\Step as StepModel;
use App\Models\File as FileModel;
use App\Domain\Induction\Step\Step;
use App\Domain\Induction\Step\Values\StepCreatedAt;
use App\Domain\Induction\Step\Values\StepDeletedAt;
use App\Domain\Induction\Step\Values\StepId;
use App\Domain\Induction\Step\Values\StepName;
use App\Domain\Induction\Step\Values\StepDescription;
use App\Domain\Induction\Step\Values\StepUpdatedAt;
use App\Domain\Induction\Step\Values\FileCreatedAt;
use App\Domain\Induction\Step\Values\FileDeletedAt;
use App\Domain\Induction\Step\Values\FileExtension;
use App\Domain\Induction\Step\Values\FileFileSubtypeId;
use App\Domain\Induction\Step\Values\FileFileTypeId;
use App\Domain\Induction\Step\Values\FileId;
use App\Domain\Induction\Step\Values\FileMetadata;
use App\Domain\Induction\Step\Values\FileMime;
use App\Domain\Induction\Step\Values\FileModelId;
use App\Domain\Induction\Step\Values\FileModelType;
use App\Domain\Induction\Step\Values\FileName;
use App\Domain\Induction\Step\Values\FilePath;
use App\Domain\Induction\Step\Values\FileSize;
use App\Domain\Induction\Step\Values\FileUpdatedAt;
use App\Domain\Induction\Step\Values\StepFormId;
use App\Domain\Induction\Step\Values\StepInductionId;
use App\Domain\Induction\Step\Values\StepRelationships;
use App\Domain\Induction\Values\InductionId;
use App\Http\Requests\Induction\Step\StepAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class StepRepository implements StepContract
{
    private $stepModel;
    private $fileModel;

    public function __construct()
    {
        $this->stepModel = new StepModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, StepAllRequest $request, InductionId $inductionId): LengthAwarePaginator
    {
        try {
            $steps = $this->stepModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['induction', 'form', 'video', 'progress' => fn ($query) => $query->when($request->filled('person_id'), fn ($query) => $query->where('person_id', $request->integer('person_id')))])
                ->where('induction_id', $inductionId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $steps->paginate($perPage ?? $steps->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los pasos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los pasos: ' . $e->getMessage());
        }
    }

    public function find(StepId $stepId): ?Step
    {
        try {
            $model = $this->stepModel->withTrashed()
                ->with(['induction', 'form', 'video'])
                ->findOrFail($stepId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El paso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el paso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el paso: ' . $e->getMessage());
        }
    }

    public function store(Step $step): Step
    {
        try {
            $model = $this->stepModel
                ->create($this->mapToModelData($step));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el paso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el paso: ' . $e->getMessage());
        }
    }

    public function update(Step $step, StepId $stepId): Step
    {
        try {
            $model = tap($this->stepModel->findOrFail($stepId->value()))
                ->update($this->mapToModelData($step))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El paso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el paso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el paso: ' . $e->getMessage());
        }
    }

    public function delete(StepId $stepId): Step
    {
        try {
            $model = tap($this->stepModel->findOrFail($stepId->value()), fn($step) => $step->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El paso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el paso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el paso: ' . $e->getMessage());
        }
    }

    public function restore(StepId $stepId): Step
    {
        try {
            $model = tap($this->stepModel->withTrashed()->findOrFail($stepId->value()), fn($step) => $step->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El paso no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el paso: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el paso: ' . $e->getMessage());
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

    private function mapToEntity($model): Step
    {
        return new Step(
            new StepId($model->id),
            new StepInductionId($model->induction_id),
            new StepName($model->name),
            new StepDescription($model->description),
            new StepFormId($model->form_id),
            new StepCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new StepUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new StepDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new StepRelationships($model->induction, $model->form, $model->video)
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

    private function mapToModelData(Step $step): array
    {
        $array = [];

        if ($step->inductionId()->value()) {
            $array['induction_id'] = $step->inductionId()->value();
        }

        if ($step->name()->value()) {
            $array['name'] = $step->name()->value();
        }

        if ($step->description()->value()) {
            $array['description'] = $step->description()->value();
        }

        if ($step->formId()->value()) {
            $array['form_id'] = $step->formId()->value();
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
