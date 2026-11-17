<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\ServiceType\Contracts\ServiceTypeContract;
use App\Models\ServiceType as ServiceTypeModel;
use App\Domain\ServiceType\ServiceType;
use App\Domain\ServiceType\Values\ServiceTypeCreatedAt;
use App\Domain\ServiceType\Values\ServiceTypeDeletedAt;
use App\Domain\ServiceType\Values\ServiceTypeId;
use App\Domain\ServiceType\Values\ServiceTypeName;
use App\Domain\ServiceType\Values\ServiceTypeDescription;
use App\Domain\ServiceType\Values\ServiceTypeItemId;
use App\Domain\ServiceType\Values\ServiceTypeSettings;
use App\Domain\ServiceType\Values\ServiceTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ServiceTypeRepository implements ServiceTypeContract
{
    private $serviceTypeModel;

    public function __construct()
    {
        $this->serviceTypeModel = new ServiceTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $serviceTypes = $this->serviceTypeModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 34)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $serviceTypes->paginate($perPage ?? $serviceTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de servicio:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de servicio:' . $e->getMessage());
        }
    }

    public function find(ServiceTypeId $serviceTypeId): ?ServiceType
    {
        try {
            $model = $this->serviceTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 34)
                ->whereKey($serviceTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de servicio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de servicio:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de servicio:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): ServiceType
    {
        return new ServiceType(
            new ServiceTypeId($model->id),
            new ServiceTypeItemId($model->item?->id ?? $model->item_id),
            new ServiceTypeName($model->name),
            new ServiceTypeDescription($model->description),
            new ServiceTypeSettings($model->settings),
            new ServiceTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ServiceTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ServiceTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
