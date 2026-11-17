<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Location\City\Contracts\CityContract;
use App\Models\City as CityModel;
use App\Domain\Location\City\City;
use App\Domain\Location\City\Values\CityCreatedAt;
use App\Domain\Location\City\Values\CityDepartamentId;
use App\Domain\Location\City\Values\CityId;
use App\Domain\Location\City\Values\CityLatitude;
use App\Domain\Location\City\Values\CityLongitude;
use App\Domain\Location\City\Values\CityName;
use App\Domain\Location\City\Values\CityUpdatedAt;
use App\Http\Requests\Location\City\CityAllRequest;
use App\Http\Requests\Location\City\CityFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CityRepository implements CityContract
{
    private $cityModel;

    public function __construct()
    {
        $this->cityModel = new CityModel;
    }

    public function all(string|null $search = null, int|null $perPage, CityAllRequest $request): LengthAwarePaginator
    {
        try {
            $cities = $this->cityModel
                ->with(['departament'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('departament_id'), fn($query) => $query->where('departament_id', $request->input('departament_id')))
                ->orderBy('name', 'asc');

            return $cities->paginate($perPage ?? $cities->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las ciudades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las ciudades: ' . $e->getMessage());
        }
    }

    public function find(CityFindRequest $request, CityId $cityId): ?City
    {
        try {
            $departament = $this->cityModel
                ->findOrFail($cityId->value());

            return $this->mapToEntity($departament);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La ciudad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la ciudad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la ciudad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): City
    {
        return new City(
            new CityId($model->id),
            new CityDepartamentId($model->departament_id),
            new CityName($model->name),
            new CityLatitude($model->latitude),
            new CityLongitude($model->longitude),
            new CityCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CityUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }
}
