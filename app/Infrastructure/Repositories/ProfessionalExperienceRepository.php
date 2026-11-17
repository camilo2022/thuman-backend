<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Profile\ProfessionalExperience\Contracts\ProfessionalExperienceContract;
use App\Domain\Profile\ProfessionalExperience\File;
use App\Models\ProfessionalExperience as ProfessionalExperienceModel;
use App\Models\File as FileModel;
use App\Domain\Profile\ProfessionalExperience\ProfessionalExperience;
use App\Domain\Profile\ProfessionalExperience\Values\FileCreatedAt;
use App\Domain\Profile\ProfessionalExperience\Values\FileDeletedAt;
use App\Domain\Profile\ProfessionalExperience\Values\FileExtension;
use App\Domain\Profile\ProfessionalExperience\Values\FileFileSubtypeId;
use App\Domain\Profile\ProfessionalExperience\Values\FileFileTypeId;
use App\Domain\Profile\ProfessionalExperience\Values\FileId;
use App\Domain\Profile\ProfessionalExperience\Values\FileMetadata;
use App\Domain\Profile\ProfessionalExperience\Values\FileMime;
use App\Domain\Profile\ProfessionalExperience\Values\FileModelId;
use App\Domain\Profile\ProfessionalExperience\Values\FileModelType;
use App\Domain\Profile\ProfessionalExperience\Values\FileName;
use App\Domain\Profile\ProfessionalExperience\Values\FilePath;
use App\Domain\Profile\ProfessionalExperience\Values\FileSize;
use App\Domain\Profile\ProfessionalExperience\Values\FileUpdatedAt;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCityId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCompanyAddress;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCompanyName;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCreatedAt;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceEndDate;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceWorkArrangementId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperiencePosition;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperiencePositionDescription;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceProfileId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceRelationships;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceStartDate;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorDialingCountryId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorName;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorPhoneNumber;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceUpdatedAt;
use App\Domain\Profile\Values\ProfileId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ProfessionalExperienceRepository implements ProfessionalExperienceContract
{
    private $professionalExperienceModel;
    private $fileModel;

    public function __construct()
    {
        $this->professionalExperienceModel = new ProfessionalExperienceModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, ProfileId $profileId): LengthAwarePaginator
    {
        try {
            $professionalExperiences = $this->professionalExperienceModel
                ->with(['profile', 'supervisor_dialing_country', 'city.departament.country', 'work_arrangement', 'file'])
                ->where('profile_id', $profileId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $professionalExperiences->paginate($perPage ?? $professionalExperiences->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las experiencia profesional: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las experiencia profesional: ' . $e->getMessage());
        }
    }

    public function find(ProfessionalExperienceId $professionalExperienceId): ?ProfessionalExperience
    {
        try {
            $model = $this->professionalExperienceModel
                ->with(['profile', 'supervisor_dialing_country', 'city.departament.country', 'work_arrangement', 'file'])
                ->findOrFail($professionalExperienceId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La experiencia profesional no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la experiencia profesional: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la experiencia profesional: ' . $e->getMessage());
        }
    }

    public function store(ProfessionalExperience $professionalExperience): ProfessionalExperience
    {
        try {
            $model = $this->professionalExperienceModel
                ->create($this->mapToModelData($professionalExperience));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la experiencia profesional: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la experiencia profesional: ' . $e->getMessage());
        }
    }

    public function update(ProfessionalExperience $professionalExperience, ProfessionalExperienceId $professionalExperienceId): ProfessionalExperience
    {
        try {
            $model = tap($this->professionalExperienceModel->findOrFail($professionalExperienceId->value()))
                ->update($this->mapToModelData($professionalExperience))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La experiencia profesional no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la experiencia profesional: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la experiencia profesional: ' . $e->getMessage());
        }
    }

    public function delete(ProfessionalExperienceId $professionalExperienceId): void
    {
        try {
            $this->professionalExperienceModel->findOrFail($professionalExperienceId->value())
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La experiencia profesional no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la experiencia profesional: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la experiencia profesional: ' . $e->getMessage());
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

    private function mapToEntity($model): ProfessionalExperience
    {
        return new ProfessionalExperience(
            new ProfessionalExperienceId($model->id),
            new ProfessionalExperienceProfileId($model->profile?->id ?? $model->profile_id),
            new ProfessionalExperienceCompanyName($model->company_name),
            new ProfessionalExperienceSupervisorName($model->supervisor_name),
            new ProfessionalExperienceSupervisorDialingCountryId($model->supervisor_dialing_country?->id ?? $model->supervisor_dialing_country_id),
            new ProfessionalExperienceSupervisorPhoneNumber($model->supervisor_phone_number),
            new ProfessionalExperienceCityId($model->city?->id ?? $model->city_id),
            new ProfessionalExperienceCompanyAddress($model->company_address),
            new ProfessionalExperienceStartDate($model->start_date?->format('Y-m-d')),
            new ProfessionalExperienceEndDate($model->end_date?->format('Y-m-d')),
            new ProfessionalExperiencePosition($model->position),
            new ProfessionalExperienceWorkArrangementId($model->work_arrangement?->id ?? $model->work_arrangement_id),
            new ProfessionalExperiencePositionDescription($model->position_description),
            new ProfessionalExperienceCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ProfessionalExperienceUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ProfessionalExperienceRelationships($model->profile, $model->supervisor_dialing_country, $model->city, $model->work_arrangement, $model->file)
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

    private function mapToModelData(ProfessionalExperience $professionalExperience): array
    {
        $array = [];

        if ($professionalExperience->profileId()->value()) {
            $array['profile_id'] = $professionalExperience->profileId()->value();
        }

        if ($professionalExperience->companyName()->value()) {
            $array['company_name'] = $professionalExperience->companyName()->value();
        }

        if ($professionalExperience->supervisorName()->value()) {
            $array['supervisor_name'] = $professionalExperience->supervisorName()->value();
        }

        if ($professionalExperience->supervisorDialingCountryId()->value()) {
            $array['supervisor_dialing_country_id'] = $professionalExperience->supervisorDialingCountryId()->value();
        }

        if ($professionalExperience->supervisorPhoneNumber()->value()) {
            $array['supervisor_phone_number'] = $professionalExperience->supervisorPhoneNumber()->value();
        }

        if ($professionalExperience->cityId()->value()) {
            $array['city_id'] = $professionalExperience->cityId()->value();
        }

        if ($professionalExperience->companyAddress()->value()) {
            $array['company_address'] = $professionalExperience->companyAddress()->value();
        }

        if ($professionalExperience->startDate()->value()) {
            $array['start_date'] = $professionalExperience->startDate()->value();
        }

        $array['end_date'] = $professionalExperience->endDate()->value();

        if ($professionalExperience->position()->value()) {
            $array['position'] = $professionalExperience->position()->value();
        }

        if ($professionalExperience->workArrangementId()->value()) {
            $array['work_arrangement_id'] = $professionalExperience->workArrangementId()->value();
        }

        if ($professionalExperience->positionDescription()->value()) {
            $array['position_description'] = $professionalExperience->positionDescription()->value();
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
