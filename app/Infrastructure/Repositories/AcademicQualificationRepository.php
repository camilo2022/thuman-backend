<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Profile\AcademicQualification\Contracts\AcademicQualificationContract;
use App\Models\AcademicQualification as AcademicQualificationModel;
use App\Models\File as FileModel;
use App\Domain\Profile\AcademicQualification\AcademicQualification;
use App\Domain\Profile\AcademicQualification\File;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicInstitution;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicLevelId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicTitle;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationCityId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationCreatedAt;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationEndDate;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationFormationStatusId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationProfileId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationRelationships;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationStartDate;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationUpdatedAt;
use App\Domain\Profile\AcademicQualification\Values\FileCreatedAt;
use App\Domain\Profile\AcademicQualification\Values\FileDeletedAt;
use App\Domain\Profile\AcademicQualification\Values\FileExtension;
use App\Domain\Profile\AcademicQualification\Values\FileFileSubtypeId;
use App\Domain\Profile\AcademicQualification\Values\FileFileTypeId;
use App\Domain\Profile\AcademicQualification\Values\FileId;
use App\Domain\Profile\AcademicQualification\Values\FileMetadata;
use App\Domain\Profile\AcademicQualification\Values\FileMime;
use App\Domain\Profile\AcademicQualification\Values\FileModelId;
use App\Domain\Profile\AcademicQualification\Values\FileModelType;
use App\Domain\Profile\AcademicQualification\Values\FileName;
use App\Domain\Profile\AcademicQualification\Values\FilePath;
use App\Domain\Profile\AcademicQualification\Values\FileSize;
use App\Domain\Profile\AcademicQualification\Values\FileUpdatedAt;
use App\Domain\Profile\Values\ProfileId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AcademicQualificationRepository implements AcademicQualificationContract
{
    private $academicQualificationModel;
    private $fileModel;

    public function __construct()
    {
        $this->academicQualificationModel = new AcademicQualificationModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, ProfileId $profileId): LengthAwarePaginator
    {
        try {
            $academicQualifications = $this->academicQualificationModel
                ->with(['profile', 'city.departament.country', 'academic_level', 'formation_status', 'file'])
                ->where('profile_id', $profileId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $academicQualifications->paginate($perPage ?? $academicQualifications->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los estudio de formación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los estudio de formación: ' . $e->getMessage());
        }
    }

    public function find(AcademicQualificationId $academicQualificationId): ?AcademicQualification
    {
        try {
            $model = $this->academicQualificationModel
                ->with(['profile', 'city.departament.country', 'academic_level', 'formation_status', 'file'])
                ->findOrFail($academicQualificationId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estudio de formación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el estudio de formación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el estudio de formación: ' . $e->getMessage());
        }
    }

    public function store(AcademicQualification $academicQualification): AcademicQualification
    {
        try {
            $model = $this->academicQualificationModel
                ->create($this->mapToModelData($academicQualification));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el estudio de formación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el estudio de formación: ' . $e->getMessage());
        }
    }

    public function update(AcademicQualification $academicQualification, AcademicQualificationId $academicQualificationId): AcademicQualification
    {
        try {
            $model = tap($this->academicQualificationModel->findOrFail($academicQualificationId->value()))
                ->update($this->mapToModelData($academicQualification))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estudio de formación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el estudio de formación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el estudio de formación: ' . $e->getMessage());
        }
    }

    public function delete(AcademicQualificationId $academicQualificationId): void
    {
        try {
            $this->academicQualificationModel->findOrFail($academicQualificationId->value())
                ->delete();

        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estudio de formación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el estudio de formación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el estudio de formación: ' . $e->getMessage());
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

    private function mapToEntity($model): AcademicQualification
    {
        return new AcademicQualification(
            new AcademicQualificationId($model->id),
            new AcademicQualificationProfileId($model->profile?->id ?? $model->profile_id),
            new AcademicQualificationCityId($model->city?->id ?? $model->city_id),
            new AcademicQualificationAcademicLevelId($model->academic_level?->id ?? $model->academic_level_id),
            new AcademicQualificationAcademicInstitution($model->academic_institution),
            new AcademicQualificationAcademicTitle($model->academic_title),
            new AcademicQualificationFormationStatusId($model->formation_status?->id ?? $model->formation_status_id),
            new AcademicQualificationStartDate($model->start_date?->format('Y-m-d')),
            new AcademicQualificationEndDate($model->end_date?->format('Y-m-d')),
            new AcademicQualificationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AcademicQualificationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AcademicQualificationRelationships($model->profile, $model->city, $model->academic_level, $model->formation_status, $model->file)
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

    private function mapToModelData(AcademicQualification $academicQualification): array
    {
        $array = [];

        if ($academicQualification->profileId()->value()) {
            $array['profile_id'] = $academicQualification->profileId()->value();
        }

        if ($academicQualification->cityId()->value()) {
            $array['city_id'] = $academicQualification->cityId()->value();
        }

        if ($academicQualification->academicLevelId()->value()) {
            $array['academic_level_id'] = $academicQualification->academicLevelId()->value();
        }

        if ($academicQualification->academicInstitution()->value()) {
            $array['academic_institution'] = $academicQualification->academicInstitution()->value();
        }

        if ($academicQualification->formationStatusId()->value()) {
            $array['formation_status_id'] = $academicQualification->formationStatusId()->value();
        }

        if ($academicQualification->academicTitle()->value()) {
            $array['academic_title'] = $academicQualification->academicTitle()->value();
        }

        if ($academicQualification->startDate()->value()) {
            $array['start_date'] = $academicQualification->startDate()->value();
        }

        $array['end_date'] = $academicQualification->endDate()->value();

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
