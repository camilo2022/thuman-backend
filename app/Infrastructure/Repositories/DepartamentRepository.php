<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Location\Departament\Contracts\DepartamentContract;
use App\Models\Departament as DepartamentModel;
use App\Domain\Location\Departament\Departament;
use App\Domain\Location\Departament\Values\DepartamentCountryId;
use App\Domain\Location\Departament\Values\DepartamentCreatedAt;
use App\Domain\Location\Departament\Values\DepartamentId;
use App\Domain\Location\Departament\Values\DepartamentLatitude;
use App\Domain\Location\Departament\Values\DepartamentLongitude;
use App\Domain\Location\Departament\Values\DepartamentName;
use App\Domain\Location\Departament\Values\DepartamentStatusCode;
use App\Domain\Location\Departament\Values\DepartamentType;
use App\Domain\Location\Departament\Values\DepartamentUpdatedAt;
use App\Http\Requests\Location\Departament\DepartamentAllRequest;
use App\Http\Requests\Location\Departament\DepartamentFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class DepartamentRepository implements DepartamentContract
{
    private $departamentModel;

    public function __construct()
    {
        $this->departamentModel = new DepartamentModel;
    }

    public function all(string|null $search = null, int|null $perPage, DepartamentAllRequest $request): LengthAwarePaginator
    {
        try {
            $departaments = $this->departamentModel
                ->with(['country', 'cities'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), fn($query) => $query->where('country_id', $request->input('country_id')))
                ->orderBy('name', 'asc');

            return $departaments->paginate($perPage ?? $departaments->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los departamentos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los departamentos: ' . $e->getMessage());
        }
    }

    public function find(DepartamentFindRequest $request, DepartamentId $departamentId): ?Departament
    {
        try {
            $departament = $this->departamentModel
                ->findOrFail($departamentId->value());

            return $this->mapToEntity($departament);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El departamento no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el departamento: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el departamento: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Departament
    {
        return new Departament(
            new DepartamentId($model->id),
            new DepartamentCountryId($model->country_id),
            new DepartamentName($model->name),
            new DepartamentStatusCode($model->status_code),
            new DepartamentType($model->type),
            new DepartamentLatitude($model->latitude),
            new DepartamentLongitude($model->longitude),
            new DepartamentCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new DepartamentUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }
}
