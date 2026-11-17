<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Enterprise\Entity\Contracts\EntityContract;
use App\Models\Entity as EntityModel;
use App\Models\File as FileModel;
use App\Domain\Enterprise\Entity\Entity;
use App\Domain\Enterprise\Entity\Image;
use App\Domain\Enterprise\Entity\Values\EntityAboutUs;
use App\Domain\Enterprise\Entity\Values\EntityCreatedAt;
use App\Domain\Enterprise\Entity\Values\EntityCountryId;
use App\Domain\Enterprise\Entity\Values\EntityDeletedAt;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Domain\Enterprise\Entity\Values\EntityName;
use App\Domain\Enterprise\Entity\Values\EntityDocumentNumber;
use App\Domain\Enterprise\Entity\Values\EntityDocumentTypeId;
use App\Domain\Enterprise\Entity\Values\EntityEnterpriseId;
use App\Domain\Enterprise\Entity\Values\EntityEntityTypeId;
use App\Domain\Enterprise\Entity\Values\EntityMission;
use App\Domain\Enterprise\Entity\Values\EntityPersonId;
use App\Domain\Enterprise\Entity\Values\EntityRelationships;
use App\Domain\Enterprise\Entity\Values\EntitySettings;
use App\Domain\Enterprise\Entity\Values\EntityUpdatedAt;
use App\Domain\Enterprise\Entity\Values\EntityVision;
use App\Domain\Enterprise\Entity\Values\ImageCreatedAt;
use App\Domain\Enterprise\Entity\Values\ImageDeletedAt;
use App\Domain\Enterprise\Entity\Values\ImageExtension;
use App\Domain\Enterprise\Entity\Values\ImageFileSubtypeId;
use App\Domain\Enterprise\Entity\Values\ImageFileTypeId;
use App\Domain\Enterprise\Entity\Values\ImageId;
use App\Domain\Enterprise\Entity\Values\ImageMetadata;
use App\Domain\Enterprise\Entity\Values\ImageMime;
use App\Domain\Enterprise\Entity\Values\ImageModelId;
use App\Domain\Enterprise\Entity\Values\ImageModelType;
use App\Domain\Enterprise\Entity\Values\ImageName;
use App\Domain\Enterprise\Entity\Values\ImagePath;
use App\Domain\Enterprise\Entity\Values\ImageSize;
use App\Domain\Enterprise\Entity\Values\ImageUpdatedAt;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Requests\Enterprise\Entity\EntityAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EntityRepository implements EntityContract
{
    private $entityModel;
    private $fileModel;

    public function __construct()
    {
        $this->entityModel = new EntityModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, EnterpriseId $enterpriseId, EntityAllRequest $request): LengthAwarePaginator
    {
        try {
            $entities = $this->entityModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['enterprise.logo', 'country', 'document_type', 'entity_type', 'person', 'images'])
                ->when($enterpriseId->value(), function ($q) use ($enterpriseId) {
                    $q->where('enterprise_id', $enterpriseId->value());
                })
                ->whereHas('enterprise')
                ->when($search, fn($query) => $query->search($search))
                ->filters($request)
                ->orderBy('id', 'asc');

            return $entities->paginate($perPage ?? $entities->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las entidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las entidades: ' . $e->getMessage());
        }
    }

    public function find(EntityId $entityId): ?Entity
    {
        try {
            $model = $this->entityModel->withTrashed()
                ->with(['enterprise.logo', 'country', 'document_type', 'entity_type', 'person', 'images'])
                ->findOrFail($entityId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la entidad: ' . $e->getMessage());
        }
    }

    public function store(Entity $entity): Entity
    {
        try {
            $model = $this->entityModel
                ->create($this->mapToModelData($entity));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la entidad: ' . $e->getMessage());
        }
    }

    public function update(Entity $entity, EntityId $entityId): Entity
    {
        try {
            $model = tap($this->entityModel->findOrFail($entityId->value()))
                ->update($this->mapToModelData($entity))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la entidad: ' . $e->getMessage());
        }
    }

    public function delete(EntityId $entityId): Entity
    {
        try {
            $model = tap($this->entityModel->findOrFail($entityId->value()), fn($entity) => $entity->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la entidad: ' . $e->getMessage());
        }
    }

    public function restore(EntityId $entityId): Entity
    {
        try {
            $model = tap($this->entityModel->withTrashed()->findOrFail($entityId->value()), fn($entity) => $entity->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la entidad: ' . $e->getMessage());
        }
    }

    public function image(Image $image): Image
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataImage($image, 'keys'), $this->mapToModelDataImage($image, 'data'));

            return $this->mapToEntityImage($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El empresa no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar la imagen: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar la imagen: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Entity
    {
        return new Entity(
            new EntityId($model->id),
            new EntityEnterpriseId($model->enterprise?->id ?? $model->enterprise_id),
            new EntityEntityTypeId($model->entity_type?->id ?? $model->entity_type_id),
            new EntityName($model->name),
            new EntityCountryId($model->country?->id ?? $model->country_id),
            new EntityDocumentTypeId($model->document_type?->id ?? $model->document_type_id),
            new EntityDocumentNumber($model->document_number),
            new EntityPersonId($model->person?->id ?? $model->person_id),
            new EntityAboutUs($model->about_us),
            new EntityMission($model->mission),
            new EntityVision($model->vision),
            new EntitySettings($model->settings),
            new EntityCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new EntityUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new EntityDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new EntityRelationships($model->enterprise, $model->country, $model->document_type, $model->entity_type, $model->person, $model->images, $model->people)
        );
    }

    private function mapToEntityImage($model): Image
    {
        return new Image(
            new ImageId($model->id),
            new ImageModelId($model->model?->id ?? $model->model_id),
            new ImageModelType($model->model_type),
            new ImageFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new ImageFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new ImageName($model->name),
            new ImagePath($model->path),
            new ImageMime($model->mime),
            new ImageExtension($model->extension),
            new ImageSize($model->size),
            new ImageMetadata($model->metadata),
            new ImageCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ImageUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ImageDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Entity $entity): array
    {
        $array = [];

        if ($entity->enterpriseId()->value()) {
            $array['enterprise_id'] = $entity->enterpriseId()->value();
        }

        if ($entity->entityTypeId()->value()) {
            $array['entity_type_id'] = $entity->entityTypeId()->value();
        }

        if ($entity->name()->value()) {
            $array['name'] = $entity->name()->value();
        }

        if ($entity->countryId()->value()) {
            $array['country_id'] = $entity->countryId()->value();
        }

        if ($entity->countryId()->value()) {
            $array['country_id'] = $entity->countryId()->value();
        }

        if ($entity->documentTypeId()->value()) {
            $array['document_type_id'] = $entity->documentTypeId()->value();
        }

        if ($entity->documentNumber()->value()) {
            $array['document_number'] = $entity->documentNumber()->value();
        }

        if ($entity->personId()->value()) {
            $array['person_id'] = $entity->personId()->value();
        }

        if ($entity->aboutUs()->value()) {
            $array['about_us'] = $entity->aboutUs()->value();
        }

        if ($entity->mission()->value()) {
            $array['mission'] = $entity->mission()->value();
        }

        if ($entity->vision()->value()) {
            $array['vision'] = $entity->vision()->value();
        }

        if ($entity->settings()->value()) {
            $array['settings'] = $entity->settings()->value();
        }

        return $array;
    }

    private function mapToModelDataImage(Image $image, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($image->modelId()->value()) {
            $array['keys']['model_id'] = $image->modelId()->value();
        }

        if ($image->modelType()->value()) {
            $array['keys']['model_type'] = $image->modelType()->value();
        }

        if ($image->fileTypeId()->value()) {
            $array['keys']['file_type_id'] = $image->fileTypeId()->value();
        }

        if ($image->fileSubtypeId()->value()) {
            $array['keys']['file_subtype_id'] = $image->fileSubtypeId()->value();
        }

        if ($image->name()->value()) {
            $array['data']['name'] = $image->name()->value();
        }

        if ($image->path()->value()) {
            $array['data']['path'] = $image->path()->value();
        }

        if ($image->mime()->value()) {
            $array['data']['mime'] = $image->mime()->value();
        }

        if ($image->extension()->value()) {
            $array['data']['extension'] = $image->extension()->value();
        }

        if ($image->size()->value()) {
            $array['data']['size'] = $image->size()->value();
        }

        if ($image->metadata()->value()) {
            $array['data']['metadata'] = $image->metadata()->value();
        }

        return $array[$index];
    }
}
