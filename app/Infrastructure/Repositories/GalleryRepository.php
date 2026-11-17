<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Enterprise\Entity\Gallery\Contracts\GalleryContract;
use App\Models\Gallery as GalleryModel;
use App\Models\Entity as EntityModel;
use App\Domain\Enterprise\Entity\Gallery\Gallery;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryCreatedAt;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryDeletedAt;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryExtension;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryFileSubtypeId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryFileTypeId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryMetadata;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryMime;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryModelId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryModelType;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryName;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryPath;
use App\Domain\Enterprise\Entity\Gallery\Values\GallerySize;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryUpdatedAt;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Gallery\GalleryAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class GalleryRepository implements GalleryContract
{
    private $galleryModel;

    public function __construct()
    {
        $this->galleryModel = new GalleryModel;
    }

    public function all(string|null $search = null, int|null $perPage, EntityId $entityId, GalleryAllRequest $request): LengthAwarePaginator
    {
        try {
            $galleries = $this->galleryModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['model'])
                ->whereHasMorph('model', [EntityModel::class], function ($query) use ($entityId) {
                    $query->where('id', $entityId->value());
                })
                ->where('file_type_id', 101)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $galleries->paginate($perPage ?? $galleries->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener la galeria de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener la galeria de la entidad: ' . $e->getMessage());
        }
    }

    public function find(GalleryId $galleryId): ?Gallery
    {
        try {
            $model = $this->galleryModel->withTrashed()
                ->with(['model'])
                ->findOrFail($galleryId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El archivo de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el archivo de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el archivo de la entidad: ' . $e->getMessage());
        }
    }

    public function store(Gallery $gallery): Gallery
    {
        try {
            $model = $this->galleryModel
                ->create($this->mapToModelData($gallery));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el archivo de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el archivo de la entidad: ' . $e->getMessage());
        }
    }

    public function delete(GalleryId $galleryId): Gallery
    {
        try {
            $model = tap($this->galleryModel->findOrFail($galleryId->value()), fn($gallery) => $gallery->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El archivo de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el archivo de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el archivo de la entidad: ' . $e->getMessage());
        }
    }

    public function destroy(GalleryId $galleryId): void
    {
        try {
            $model = $this->galleryModel->findOrFail($galleryId->value());

            if (Storage::exists($model->path)) Storage::delete($model->path);

            $model->forceDelete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El archivo de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar permanentemente el archivo de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar permanentemente el archivo de la entidad: ' . $e->getMessage());
        }
    }

    public function restore(GalleryId $galleryId): Gallery
    {
        try {
            $model = tap($this->galleryModel->withTrashed()->findOrFail($galleryId->value()), fn($gallery) => $gallery->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El archivo de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el archivo de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el archivo de la entidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Gallery
    {
        return new Gallery(
            new GalleryId($model->id),
            new GalleryModelId($model->model?->id ?? $model->model_id),
            new GalleryModelType($model->model_type),
            new GalleryFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new GalleryFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new GalleryName($model->name),
            new GalleryPath($model->path),
            new GalleryMime($model->mime),
            new GalleryExtension($model->extension),
            new GallerySize($model->size),
            new GalleryMetadata($model->metadata),
            new GalleryCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new GalleryUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new GalleryDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Gallery $gallery): array
    {
        $array = [];

        if ($gallery->modelId()->value()) {
            $array['model_id'] = $gallery->modelId()->value();
        }

        if ($gallery->modelType()->value()) {
            $array['model_type'] = $gallery->modelType()->value();
        }

        if ($gallery->fileTypeId()->value()) {
            $array['file_type_id'] = $gallery->fileTypeId()->value();
        }

        if ($gallery->fileSubtypeId()->value()) {
            $array['file_subtype_id'] = $gallery->fileSubtypeId()->value();
        }

        if ($gallery->name()->value()) {
            $array['name'] = $gallery->name()->value();
        }

        if ($gallery->path()->value()) {
            $array['path'] = $gallery->path()->value();
        }

        if ($gallery->mime()->value()) {
            $array['mime'] = $gallery->mime()->value();
        }

        if ($gallery->extension()->value()) {
            $array['extension'] = $gallery->extension()->value();
        }

        if ($gallery->size()->value()) {
            $array['size'] = $gallery->size()->value();
        }

        if ($gallery->metadata()->value()) {
            $array['metadata'] = $gallery->metadata()->value();
        }

        return $array;
    }
}
