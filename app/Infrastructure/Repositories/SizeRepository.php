<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Size\Contracts\SizeContract;
use App\Models\Size as SizeModel;
use App\Domain\Size\Size;
use App\Domain\Size\Values\SizeCreatedAt;
use App\Domain\Size\Values\SizeDeletedAt;
use App\Domain\Size\Values\SizeId;
use App\Domain\Size\Values\SizeName;
use App\Domain\Size\Values\SizeDescription;
use App\Domain\Size\Values\SizeItemId;
use App\Domain\Size\Values\SizeSettings;
use App\Domain\Size\Values\SizeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SizeRepository implements SizeContract
{
    private $relationshipModel;

    public function __construct()
    {
        $this->relationshipModel = new SizeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $relationships = $this->relationshipModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 53)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $relationships->paginate($perPage ?? $relationships->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las tallas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las tallas: ' . $e->getMessage());
        }
    }

    public function find(SizeId $relationshipId): ?Size
    {
        try {
            $model = $this->relationshipModel->withTrashed()
                ->with(['item'])->where('item_id', 53)
                ->whereKey($relationshipId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La talla no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la talla: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la talla: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Size
    {
        return new Size(
            new SizeId($model->id),
            new SizeItemId($model->item?->id ?? $model->item_id),
            new SizeName($model->name),
            new SizeDescription($model->description),
            new SizeSettings($model->settings),
            new SizeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SizeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SizeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
