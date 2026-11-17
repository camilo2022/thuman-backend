<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\FileType\Contracts\FileTypeContract;
use App\Models\FileType as FileTypeModel;
use App\Domain\FileType\FileType;
use App\Domain\FileType\Values\FileTypeCreatedAt;
use App\Domain\FileType\Values\FileTypeDeletedAt;
use App\Domain\FileType\Values\FileTypeId;
use App\Domain\FileType\Values\FileTypeName;
use App\Domain\FileType\Values\FileTypeDescription;
use App\Domain\FileType\Values\FileTypeItemId;
use App\Domain\FileType\Values\FileTypeRelationships;
use App\Domain\FileType\Values\FileTypeSettings;
use App\Domain\FileType\Values\FileTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FileTypeRepository implements FileTypeContract
{
    private $fileTypeModel;

    public function __construct()
    {
        $this->fileTypeModel = new FileTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $fileTypes = $this->fileTypeModel->withTrashed()
                ->with(['item', 'file_subtypes'])
                ->where('item_id', 16)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $fileTypes->paginate($perPage ?? $fileTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de archivos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de archivos: ' . $e->getMessage());
        }
    }

    public function find(FileTypeId $fileTypeId): ?FileType
    {
        try {
            $model = $this->fileTypeModel->withTrashed()
                ->with(['item', 'file_subtypes'])->where('item_id', 16)
                ->whereKey($fileTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de archivo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de archivo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de archivo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): FileType
    {
        return new FileType(
            new FileTypeId($model->id),
            new FileTypeItemId($model->item?->id ?? $model->item_id),
            new FileTypeName($model->name),
            new FileTypeDescription($model->description),
            new FileTypeSettings($model->settings),
            new FileTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FileTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FileTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new FileTypeRelationships($model->file_subtypes)
        );
    }
}
