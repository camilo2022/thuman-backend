<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Enterprise\Entity\Ally\Contracts\AllyContract;
use App\Models\Ally as AllyModel;
use App\Domain\Enterprise\Entity\Ally\Ally;
use App\Domain\Enterprise\Entity\Ally\Values\AllyCountryId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyCreatedAt;
use App\Domain\Enterprise\Entity\Ally\Values\AllyDeletedAt;
use App\Domain\Enterprise\Entity\Ally\Values\AllyEntityId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyName;
use App\Domain\Enterprise\Entity\Ally\Values\AllyDescription;
use App\Domain\Enterprise\Entity\Ally\Values\AllyRelationships;
use App\Domain\Enterprise\Entity\Ally\Values\AllySettings;
use App\Domain\Enterprise\Entity\Ally\Values\AllyUpdatedAt;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Ally\AllyAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AllyRepository implements AllyContract
{
    private $allyModel;

    public function __construct()
    {
        $this->allyModel = new AllyModel;
    }

    public function all(string|null $search = null, int|null $perPage, EntityId $entityId, AllyAllRequest $request): LengthAwarePaginator
    {
        try {
            $allys = $this->allyModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['entity'])
                ->where('entity_id', $entityId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $allys->paginate($perPage ?? $allys->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las sedes de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las sedes de la entidad: ' . $e->getMessage());
        }
    }

    public function find(AllyId $allyId): ?Ally
    {
        try {
            $model = $this->allyModel->withTrashed()
                ->with(['entity'])
                ->findOrFail($allyId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function store(Ally $ally): Ally
    {
        try {
            $model = $this->allyModel
                ->create($this->mapToModelData($ally));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function update(Ally $ally, AllyId $allyId): Ally
    {
        try {
            $model = tap($this->allyModel->findOrFail($allyId->value()))
                ->update($this->mapToModelData($ally))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function delete(AllyId $allyId): Ally
    {
        try {
            $model = tap($this->allyModel->findOrFail($allyId->value()), fn($ally) => $ally->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la sede de la entidad: ' . $e->getMessage());
        }
    }

    public function restore(AllyId $allyId): Ally
    {
        try {
            $model = tap($this->allyModel->withTrashed()->findOrFail($allyId->value()), fn($ally) => $ally->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La sede de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la sede de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la sede de la entidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Ally
    {
        return new Ally(
            new AllyId($model->id),
            new AllyEntityId($model->entity?->id ?? $model->entity_id),
            new AllyName($model->name),
            new AllyCountryId($model->country?->id ?? $model->country_id),
            new AllyDescription($model->description),
            new AllySettings($model->settings),
            new AllyCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AllyUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AllyDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new AllyRelationships($model->entity, $model->city)
        );
    }

    private function mapToModelData(Ally $ally): array
    {
        $array = [];

        if ($ally->entityId()->value()) {
            $array['entity_id'] = $ally->entityId()->value();
        }

        if ($ally->countryId()->value()) {
            $array['country_id'] = $ally->countryId()->value();
        }

        if ($ally->name()->value()) {
            $array['name'] = $ally->name()->value();
        }

        if ($ally->description()->value()) {
            $array['description'] = $ally->description()->value();
        }

        if ($ally->settings()->value()) {
            $array['settings'] = $ally->settings()->value();
        }

        return $array;
    }
}
