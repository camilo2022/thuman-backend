<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\FileSubtype\Contracts\FileSubtypeContract;
use App\Models\FileSubtype as FileSubtypeModel;
use App\Domain\FileSubtype\FileSubtype;
use App\Domain\FileSubtype\Values\FileSubtypeCreatedAt;
use App\Domain\FileSubtype\Values\FileSubtypeDeletedAt;
use App\Domain\FileSubtype\Values\FileSubtypeId;
use App\Domain\FileSubtype\Values\FileSubtypeName;
use App\Domain\FileSubtype\Values\FileSubtypeDescription;
use App\Domain\FileSubtype\Values\FileSubtypeItemId;
use App\Domain\FileSubtype\Values\FileSubtypeRelationships;
use App\Domain\FileSubtype\Values\FileSubtypeSettings;
use App\Domain\FileSubtype\Values\FileSubtypeUpdatedAt;
use App\Http\Requests\FileSubtype\FileSubtypeAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FileSubtypeRepository implements FileSubtypeContract
{
    private $fileSubtypeModel;

    public function __construct()
    {
        $this->fileSubtypeModel = new FileSubtypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, FileSubtypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $fileSubtypes = $this->fileSubtypeModel->withTrashed()
                ->with(['item', 'file_types'])
                ->where('item_id', 17)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('file_type_id'), function ($q) use ($request) {
                    $q->whereHas('file_types', fn ($sq) => $sq->where('id', $request->integer('file_type_id')));
                })
                ->orderBy('id', 'asc');

            return $fileSubtypes->paginate($perPage ?? $fileSubtypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de archivos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de archivos: ' . $e->getMessage());
        }
    }

    public function find(FileSubtypeId $fileSubtypeId): ?FileSubtype
    {
        try {
            $model = $this->fileSubtypeModel->withTrashed()
                ->with(['item', 'file_types'])->where('item_id', 17)
                ->whereKey($fileSubtypeId->value())
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

    private function mapToEntity($model): FileSubtype
    {
        return new FileSubtype(
            new FileSubtypeId($model->id),
            new FileSubtypeItemId($model->item?->id ?? $model->item_id),
            new FileSubtypeName($model->name),
            new FileSubtypeDescription($model->description),
            new FileSubtypeSettings($model->settings),
            new FileSubtypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FileSubtypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FileSubtypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new FileSubtypeRelationships($model->file_types)
        );
    }
}
