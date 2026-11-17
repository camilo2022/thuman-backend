<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\HealthEntityType\Contracts\HealthEntityTypeContract;
use App\Models\HealthEntityType as HealthEntityTypeModel;
use App\Domain\HealthEntityType\HealthEntityType;
use App\Domain\HealthEntityType\Values\HealthEntityTypeCreatedAt;
use App\Domain\HealthEntityType\Values\HealthEntityTypeDeletedAt;
use App\Domain\HealthEntityType\Values\HealthEntityTypeId;
use App\Domain\HealthEntityType\Values\HealthEntityTypeName;
use App\Domain\HealthEntityType\Values\HealthEntityTypeDescription;
use App\Domain\HealthEntityType\Values\HealthEntityTypeItemId;
use App\Domain\HealthEntityType\Values\HealthEntityTypeSettings;
use App\Domain\HealthEntityType\Values\HealthEntityTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class HealthEntityTypeRepository implements HealthEntityTypeContract
{
    private $healthEntityTypeModel;

    public function __construct()
    {
        $this->healthEntityTypeModel = new HealthEntityTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $healthEntityTypes = $this->healthEntityTypeModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 25)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $healthEntityTypes->paginate($perPage ?? $healthEntityTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de entidades de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de entidades de salud: ' . $e->getMessage());
        }
    }

    public function find(HealthEntityTypeId $healthEntityTypeId): ?HealthEntityType
    {
        try {
            $model = $this->healthEntityTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 25)
                ->whereKey($healthEntityTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de entidad de salud no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de entidad de salud: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de entidad de salud: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): HealthEntityType
    {
        return new HealthEntityType(
            new HealthEntityTypeId($model->id),
            new HealthEntityTypeItemId($model->item?->id ?? $model->item_id),
            new HealthEntityTypeName($model->name),
            new HealthEntityTypeDescription($model->description),
            new HealthEntityTypeSettings($model->settings),
            new HealthEntityTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new HealthEntityTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new HealthEntityTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
