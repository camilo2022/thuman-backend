<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Profile\Contracts\ProfileContract;
use App\Domain\Profile\File;
use App\Models\Profile as ProfileModel;
use App\Models\File as FileModel;
use App\Domain\Profile\Profile;
use App\Domain\Profile\Values\FileCreatedAt;
use App\Domain\Profile\Values\FileDeletedAt;
use App\Domain\Profile\Values\FileExtension;
use App\Domain\Profile\Values\FileFileSubtypeId;
use App\Domain\Profile\Values\FileFileTypeId;
use App\Domain\Profile\Values\FileId;
use App\Domain\Profile\Values\FileMetadata;
use App\Domain\Profile\Values\FileMime;
use App\Domain\Profile\Values\FileModelId;
use App\Domain\Profile\Values\FileModelType;
use App\Domain\Profile\Values\FileName;
use App\Domain\Profile\Values\FilePath;
use App\Domain\Profile\Values\FileSize;
use App\Domain\Profile\Values\FileUpdatedAt;
use App\Domain\Profile\Values\ProfileCreatedAt;
use App\Domain\Profile\Values\ProfileId;
use App\Domain\Profile\Values\ProfilePersonId;
use App\Domain\Profile\Values\ProfileDescription;
use App\Domain\Profile\Values\ProfileRelationships;
use App\Domain\Profile\Values\ProfileTitle;
use App\Domain\Profile\Values\ProfileUpdatedAt;
use App\Http\Requests\Profile\ProfileFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ProfileRepository implements ProfileContract
{
    private $profileModel;
    private $fileModel;

    public function __construct()
    {
        $this->profileModel = new ProfileModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $profiles = $this->profileModel
                ->with(['person'])
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $profiles->paginate($perPage ?? $profiles->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los perfils: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los perfils: ' . $e->getMessage());
        }
    }

    public function find(ProfileFindRequest $request, ProfileId $profileId): ?Profile
    {
        try {
            $model = $this->profileModel
                ->with(['person'])
                ->when($profileId->value(), function ($query) use ($profileId) {
                    $query->where('id', $profileId->value());
                })
                ->when(!$profileId->value(), function ($query) use ($request) {
                    $query->when($request->filled('person_id'), function ($query) use ($request) {
                        $query->where('person_id', $request->input('person_id'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El perfil no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el perfil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el perfil: ' . $e->getMessage());
        }
    }

    public function store(Profile $profile): Profile
    {
        try {
            $model = $this->profileModel
                ->create($this->mapToModelData($profile));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el perfil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el perfil: ' . $e->getMessage());
        }
    }

    public function update(Profile $profile, ProfileId $profileId): Profile
    {
        try {
            $model = tap($this->profileModel->findOrFail($profileId->value()))
                ->update($this->mapToModelData($profile))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El perfil no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el perfil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el perfil: ' . $e->getMessage());
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

    private function mapToEntity($model): Profile
    {
        return new Profile(
            new ProfileId($model->id),
            new ProfilePersonId($model->person?->id ?? $model->person_id),
            new ProfileTitle($model->title),
            new ProfileDescription($model->description),
            new ProfileCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ProfileUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ProfileRelationships($model->file)
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

    private function mapToModelData(Profile $profile): array
    {
        $array = [];

        if ($profile->personId()->value()) {
            $array['person_id'] = $profile->personId()->value();
        }

        if ($profile->title()->value()) {
            $array['title'] = $profile->title()->value();
        }

        if ($profile->description()->value()) {
            $array['description'] = $profile->description()->value();
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
