<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\ServiceProvider\Contracts\ServiceProviderContract;
use App\Models\ServiceProvider as ServiceProviderModel;
use App\Domain\ServiceProvider\ServiceProvider;
use App\Domain\ServiceProvider\Values\ServiceProviderCountryId;
use App\Domain\ServiceProvider\Values\ServiceProviderCreatedAt;
use App\Domain\ServiceProvider\Values\ServiceProviderDeletedAt;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Domain\ServiceProvider\Values\ServiceProviderName;
use App\Domain\ServiceProvider\Values\ServiceProviderDescription;
use App\Domain\ServiceProvider\Values\ServiceProviderItemId;
use App\Domain\ServiceProvider\Values\ServiceProviderRelationships;
use App\Domain\ServiceProvider\Values\ServiceProviderServiceTypeId;
use App\Domain\ServiceProvider\Values\ServiceProviderSettings;
use App\Domain\ServiceProvider\Values\ServiceProviderUpdatedAt;
use App\Http\Requests\ServiceProvider\ServiceProviderAllRequest;
use App\Models\ExamPolygraph;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ServiceProviderRepository implements ServiceProviderContract
{
    private $serviceProviderModel;

    public function __construct()
    {
        $this->serviceProviderModel = new ServiceProviderModel;
    }

    public function all(string|null $search = null, int|null $perPage, ServiceProviderAllRequest $request): LengthAwarePaginator
    {
        try {
            $serviceProviders = $this->serviceProviderModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'service_types', 'countries'])
                ->where('item_id', 35)
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereHas('countries', fn($r) => $r->where('id', $request->integer('country_id')));
                })
                ->when($request->filled('service_type_id'), function ($q) use ($request) {
                    $q->whereHas('service_types', fn($r) => $r->where('id', $request->integer('service_type_id')));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $serviceProviders->paginate($perPage ?? $serviceProviders->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los proveedores de servicios: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los proveedores de servicios: ' . $e->getMessage());
        }
    }

    public function find(ServiceProviderId $serviceProviderId): ?ServiceProvider
    {
        try {
            $model = $this->serviceProviderModel->withTrashed()
                ->with(['item', 'service_types', 'countries'])->where('item_id', 35)
                ->findOrFail($serviceProviderId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El proveedor de servicio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el proveedor de servicio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el proveedor de servicio: ' . $e->getMessage());
        }
    }

    public function store(ServiceProvider $serviceProvider): ServiceProvider
    {
        try {
            $model = $this->serviceProviderModel
                ->create($this->mapToModelData($serviceProvider));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el proveedor de servicio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el proveedor de servicio: ' . $e->getMessage());
        }
    }

    public function update(ServiceProvider $serviceProvider, ServiceProviderId $serviceProviderId): ServiceProvider
    {
        try {
            $model = tap($this->serviceProviderModel->where('item_id', 35)->whereKey($serviceProviderId->value())->firstOrFail())
                ->update($this->mapToModelData($serviceProvider))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El proveedor de servicio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el proveedor de servicio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el proveedor de servicio: ' . $e->getMessage());
        }
    }

    public function toggle(ServiceProvider $serviceProvider, ServiceProviderId $serviceProviderId): ServiceProvider
    {
        try {
            $model = $this->serviceProviderModel->where('item_id', 35)->whereKey($serviceProviderId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $serviceProvider->countryId()->value()
            ]));

            $model->service_types()->toggle(array_filter([
                $serviceProvider->serviceTypeId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El proveedor de servicio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el proveedor de servicio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el proveedor de servicio: ' . $e->getMessage());
        }
    }

    public function delete(ServiceProviderId $serviceProviderId): ServiceProvider
    {
        try {
            $model = $this->serviceProviderModel->where('item_id', 35)->whereKey($serviceProviderId->value())->firstOrFail();

            $isUsed = ExamPolygraph::where('service_provider_id', $serviceProviderId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El proveedor de servicio no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el proveedor de servicio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el proveedor de servicio: ' . $e->getMessage());
        }
    }

    public function restore(ServiceProviderId $serviceProviderId): ServiceProvider
    {
        try {
            $model = tap($this->serviceProviderModel->withTrashed()->where('item_id', 35)->whereKey($serviceProviderId->value())->firstOrFail(), fn($serviceProvider) => $serviceProvider->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El proveedor de servicio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el proveedor de servicio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el proveedor de servicio: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): ServiceProvider
    {
        return new ServiceProvider(
            new ServiceProviderId($model->id),
            new ServiceProviderItemId($model->item_id),
            new ServiceProviderName($model->name),
            new ServiceProviderDescription($model->description),
            new ServiceProviderSettings($model->settings),
            new ServiceProviderServiceTypeId(),
            new ServiceProviderCountryId(),
            new ServiceProviderCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ServiceProviderUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ServiceProviderDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new ServiceProviderRelationships($model->service_types, $model->countries)
        );
    }

    private function mapToModelData(ServiceProvider $serviceProvider): array
    {
        $array = [];

        if ($serviceProvider->itemId()->value()) {
            $array['item_id'] = $serviceProvider->itemId()->value();
        }

        if ($serviceProvider->name()->value()) {
            $array['name'] = $serviceProvider->name()->value();
        }

        if ($serviceProvider->description()->value()) {
            $array['description'] = $serviceProvider->description()->value();
        }

        if ($serviceProvider->settings()->value()) {
            $array['settings'] = $serviceProvider->settings()->value();
        }

        return $array;
    }
}
