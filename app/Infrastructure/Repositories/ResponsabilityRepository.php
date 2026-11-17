<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Position\Responsability\Contracts\ResponsabilityContract;
use App\Models\Responsability as ResponsabilityModel;
use App\Domain\Position\Responsability\Responsability;
use App\Domain\Position\Responsability\Values\ResponsabilityCreatedAt;
use App\Domain\Position\Responsability\Values\ResponsabilityDescription;
use App\Domain\Position\Responsability\Values\ResponsabilityId;
use App\Domain\Position\Responsability\Values\ResponsabilityName;
use App\Domain\Position\Responsability\Values\ResponsabilityPositionId;
use App\Domain\Position\Responsability\Values\ResponsabilityUpdatedAt;
use App\Domain\Position\Values\PositionId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ResponsabilityRepository implements ResponsabilityContract
{
    private $responsabilityModel;

    public function __construct()
    {
        $this->responsabilityModel = new ResponsabilityModel;
    }

    public function all(string|null $search = null, int|null $perPage, PositionId $positionId): LengthAwarePaginator
    {
        try {
            $responsabilities = $this->responsabilityModel
                ->with(['position'])
                ->where('position_id', $positionId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $responsabilities->paginate($perPage ?? $responsabilities->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las responsabilidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las responsabilidades: ' . $e->getMessage());
        }
    }

    public function find(ResponsabilityId $responsabilityId): ?Responsability
    {
        try {
            $model = $this->responsabilityModel
                ->with(['position'])
                ->findOrFail($responsabilityId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La responsabilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la responsabilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la responsabilidad: ' . $e->getMessage());
        }
    }

    public function store(Responsability $responsability): Responsability
    {
        try {
            $model = $this->responsabilityModel
                ->create($this->mapToModelData($responsability));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la responsabilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la responsabilidad: ' . $e->getMessage());
        }
    }

    public function update(Responsability $responsability, ResponsabilityId $responsabilityId): Responsability
    {
        try {
            $model = tap($this->responsabilityModel->findOrFail($responsabilityId->value()))
                ->update($this->mapToModelData($responsability))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La responsabilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la responsabilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la responsabilidad: ' . $e->getMessage());
        }
    }

    public function delete(ResponsabilityId $responsabilityId): void
    {
        try {
            $this->responsabilityModel->findOrFail($responsabilityId->value())
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La responsabilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la responsabilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la responsabilidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Responsability
    {
        return new Responsability(
            new ResponsabilityId($model->id),
            new ResponsabilityPositionId($model->position?->id ?? $model->position_id),
            new ResponsabilityName($model->name),
            new ResponsabilityDescription($model->description),
            new ResponsabilityCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ResponsabilityUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Responsability $responsability): array
    {
        $array = [];

        if ($responsability->positionId()->value()) {
            $array['position_id'] = $responsability->positionId()->value();
        }

        if ($responsability->name()->value()) {
            $array['name'] = $responsability->name()->value();
        }

        if ($responsability->description()->value()) {
            $array['description'] = $responsability->description()->value();
        }

        return $array;
    }
}
