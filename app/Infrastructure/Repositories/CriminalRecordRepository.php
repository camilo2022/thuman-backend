<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\CriminalRecord\Contracts\CriminalRecordContract;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordApplicationId;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordCreatedAt;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordId;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordRelationships;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordStatus;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordUpdatedAt;
use App\Domain\Vacant\Application\CriminalRecord\CriminalRecord;
use App\Domain\Vacant\Application\CriminalRecord\File;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordObservation;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileCreatedAt;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileDeletedAt;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileExtension;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileFileSubtypeId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileFileTypeId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileMetadata;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileMime;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileModelId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileModelType;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileName;
use App\Domain\Vacant\Application\CriminalRecord\Values\FilePath;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileSize;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileUpdatedAt;
use App\Http\Requests\Vacant\Application\CriminalRecord\CriminalRecordFindRequest;
use App\Models\CriminalRecord as CriminalRecordModel;
use App\Models\File as FileModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CriminalRecordRepository implements CriminalRecordContract
{
    private $criminalRecordModel;
    private $fileModel;

    public function __construct()
    {
        $this->criminalRecordModel = new CriminalRecordModel;
        $this->fileModel = new FileModel;
    }

    public function find(CriminalRecordFindRequest $request, CriminalRecordId $criminalRecordId): ?CriminalRecord
    {
        try {
            $model = $this->criminalRecordModel
                ->with(['application', 'file'])
                ->when($criminalRecordId->value(), function ($query) use ($criminalRecordId) {
                    $query->where('id', $criminalRecordId->value());
                })
                ->when(!$criminalRecordId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de antecedentes criminales no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro de antecedentes criminales: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro de antecedentes criminales: ' . $e->getMessage());
        }
    }

    public function store(CriminalRecord $criminalRecord): CriminalRecord
    {
        try {
            $model = $this->criminalRecordModel
                ->create($this->mapToModelData($criminalRecord));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro de antecedentes criminales: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro de antecedentes criminales: ' . $e->getMessage());
        }
    }

    public function update(CriminalRecord $criminalRecord, CriminalRecordId $criminalRecordId): CriminalRecord
    {
        try {
            $model = tap($this->criminalRecordModel->findOrFail($criminalRecordId->value()))
                ->update($this->mapToModelData($criminalRecord))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de antecedentes criminales no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro de antecedentes criminales: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro de antecedentes criminales: ' . $e->getMessage());
        }
    }

    public function file(File $file): File
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataFile($file, 'keys'), $this->mapToModelDataFile($file, 'data'));

            return $this->mapToEntityFile($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El file no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar el file: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar el file: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): CriminalRecord
    {
        return new CriminalRecord(
            new CriminalRecordId($model->id),
            new CriminalRecordApplicationId($model->application?->id ?? $model->application_id),
            new CriminalRecordObservation($model->observation),
            new CriminalRecordStatus($model->status),
            new CriminalRecordCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CriminalRecordUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CriminalRecordRelationships($model->application, $model->file)
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

    private function mapToModelData(CriminalRecord $criminalRecord): array
    {
        $array = [];

        if ($criminalRecord->applicationId()->value()) {
            $array['application_id'] = $criminalRecord->applicationId()->value();
        }

        if ($criminalRecord->observation()->value()) {
            $array['observation'] = $criminalRecord->observation()->value();
        }

        if ($criminalRecord->status()->value()) {
            $array['status'] = $criminalRecord->status()->value();
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
