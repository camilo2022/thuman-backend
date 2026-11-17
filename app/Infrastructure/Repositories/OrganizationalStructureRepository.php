<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\OrganizationalStructure\Contracts\OrganizationalStructureContract;
use App\Domain\OrganizationalStructure\OrganizationalStructure;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureCreatedAt;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureDeletedAt;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureEndDate;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureEntityId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureParentPositionId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructurePositionId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureReason;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureRelationships;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureStartDate;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureStatus;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureUpdatedAt;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureAllRequest;
use App\Models\OrganizationalStructure as OrganizationalStructureModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class OrganizationalStructureRepository implements OrganizationalStructureContract
{
    private $organizationalStructureModel;

    public function __construct()
    {
        $this->organizationalStructureModel = new OrganizationalStructureModel;
    }

    public function all(string|null $search = null, int|null $perPage, OrganizationalStructureAllRequest $request): LengthAwarePaginator
    {
        try {
            $organizationalStructures = $this->organizationalStructureModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['entity', 'position', 'parentPosition'])
                ->when(
                    $request->filled('entity_id'),
                    fn($query) => $query->where('entity_id', $request->input('entity_id'))
                )
                ->when(
                    $request->filled('position_id'),
                    fn($query) => $query->where(
                        'position_id',
                        $request->input('position_id')
                    )
                )
                ->when(
                    $request->filled('parent_position_id'),
                    fn($query) => $query->where(
                        'parent_position_id',
                        $request->input('parent_position_id')
                    )
                )
                ->when(
                    $request->filled('status'),
                    fn($query) => $query->where(
                        'status',
                        $request->input('status')
                    )
                )
                ->when(
                    $search,
                    fn($query) => $query->search($search)
                )
                ->orderBy('id', 'asc');

            return $organizationalStructures->paginate(
                $perPage ?? $organizationalStructures->count()
            );
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al obtener las estructuras organizacionales: '
                    . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al obtener las estructuras organizacionales: '
                    . $e->getMessage()
            );
        }
    }

    public function find(OrganizationalStructureId $organizationalStructureId): ?OrganizationalStructure
    {
        try {
            $model = $this->organizationalStructureModel
                ->withTrashed()
                ->with([
                    'entity',
                    'position',
                    'parentPosition',
                ])
                ->findOrFail($organizationalStructureId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(
                404,
                'La estructura organizacional no existe: '
                    . $e->getMessage()
            );
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al buscar la estructura organizacional: '
                    . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al buscar la estructura organizacional: '
                    . $e->getMessage()
            );
        }
    }

    public function store(OrganizationalStructure $organizationalStructure): OrganizationalStructure
    {
        try {
            $model = $this->organizationalStructureModel
                ->create(
                    $this->mapToModelData($organizationalStructure)
                );

            $model->load([
                'entity',
                'position',
                'parentPosition',
            ]);

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al guardar la estructura organizacional: '
                    . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al guardar la estructura organizacional: '
                    . $e->getMessage()
            );
        }
    }

    public function update(OrganizationalStructure $organizationalStructure, OrganizationalStructureId $organizationalStructureId): OrganizationalStructure
    {
        try {
            $model = tap(
                $this->organizationalStructureModel
                    ->findOrFail($organizationalStructureId->value())
            )
                ->update(
                    $this->mapToModelData($organizationalStructure)
                )
                ->refresh();

            $model->load([
                'entity',
                'position',
                'parentPosition',
            ]);

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(
                404,
                'La estructura organizacional no existe: '
                    . $e->getMessage()
            );
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al actualizar la estructura organizacional: '
                    . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al actualizar la estructura organizacional: '
                    . $e->getMessage()
            );
        }
    }

    public function delete(OrganizationalStructureId $organizationalStructureId): OrganizationalStructure
    {
        try {
            $model = tap(
                $this->organizationalStructureModel
                    ->findOrFail($organizationalStructureId->value()),
                fn($organizationalStructure) => $organizationalStructure->delete()
            );

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(
                404,
                'La estructura organizacional no existe: '
                    . $e->getMessage()
            );
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al eliminar la estructura organizacional: '
                    . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al eliminar la estructura organizacional: '
                    . $e->getMessage()
            );
        }
    }

    public function restore(OrganizationalStructureId $organizationalStructureId): OrganizationalStructure
    {
        try {
            $model = tap(
                $this->organizationalStructureModel
                    ->withTrashed()
                    ->findOrFail($organizationalStructureId->value()),
                fn($organizationalStructure) => $organizationalStructure->restore()
            );

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(
                404,
                'La estructura organizacional no existe: '
                    . $e->getMessage()
            );
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al restaurar la estructura organizacional: '
                    . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al restaurar la estructura organizacional: '
                    . $e->getMessage()
            );
        }
    }

    public function updateStatus(OrganizationalStructureId $organizationalStructureId, OrganizationalStructureStatus $status): OrganizationalStructure
    {
        try {
            $model = $this->organizationalStructureModel->findOrFail($organizationalStructureId->value());

            $model->update(['status' => $status->value()]);
            $model->refresh();
            $model->load(['entity', 'position', 'parentPosition']);

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(
                404,
                'La estructura organizacional no existe: ' . $e->getMessage()
            );
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al actualizar el estado de la estructura organizacional: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al actualizar el estado de la estructura organizacional: ' . $e->getMessage()
            );
        }
    }

    public function existsPath(int $entityId, int $fromPositionId, int $toPositionId, ?int $exceptId = null): bool
    {
        try {
            $currentPositionId = $fromPositionId;
            $visited = [];

            while ($currentPositionId !== $toPositionId) {

                if (in_array($currentPositionId, $visited, true)) {
                    return false;
                }

                $visited[] = $currentPositionId;

                $organizationalStructure = $this->organizationalStructureModel
                    ->where('entity_id', $entityId)
                    ->where('position_id', $currentPositionId)
                    ->whereIn('status', [
                        OrganizationalStructureModel::PENDING,
                        OrganizationalStructureModel::APPROVED
                    ])
                    ->whereNull('deleted_at')
                    ->when(
                        $exceptId !== null,
                        function ($query) use ($exceptId) {
                            $query->where('id', '!=', $exceptId);
                        }
                    )
                    ->first();

                if (!$organizationalStructure) {
                    return false;
                }

                if ($organizationalStructure->parent_position_id === null) {
                    return false;
                }

                $currentPositionId = (int) $organizationalStructure->parent_position_id;
            }

            return true;
        } catch (QueryException $e) {
            throw new HttpException(
                500,
                'Error al verificar la estructura organizacional: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al verificar la estructura organizacional: ' . $e->getMessage()
            );
        }
    }

    private function mapToEntity($model): OrganizationalStructure
    {
        return new OrganizationalStructure(
            new OrganizationalStructureId($model->id),
            new OrganizationalStructureEntityId($model->entity?->id ?? $model->entity_id),
            new OrganizationalStructurePositionId($model->position?->id ?? $model->position_id),
            new OrganizationalStructureParentPositionId($model->parentPosition?->id ?? $model->parent_position_id),
            new OrganizationalStructureStartDate($model->start_date?->format('Y-m-d')),
            new OrganizationalStructureEndDate($model->end_date?->format('Y-m-d')),
            new OrganizationalStructureReason($model->reason),
            new OrganizationalStructureStatus($model->status),
            new OrganizationalStructureCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new OrganizationalStructureUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new OrganizationalStructureDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new OrganizationalStructureRelationships($model->entity, $model->position, $model->parentPosition)
        );
    }

    private function mapToModelData(
        OrganizationalStructure $organizationalStructure
    ): array {
        $array = [];

        if ($organizationalStructure->entityId()->value() !== null) {
            $array['entity_id'] =
                $organizationalStructure->entityId()->value();
        }

        if ($organizationalStructure->positionId()->value() !== null) {
            $array['position_id'] =
                $organizationalStructure->positionId()->value();
        }

        $array['parent_position_id'] = $organizationalStructure->parentPositionId()->value();

        if ($organizationalStructure->startDate()->value() !== null) {
            $array['start_date'] =
                $organizationalStructure->startDate()->value();
        }

        if ($organizationalStructure->endDate()->value() !== null) {
            $array['end_date'] =
                $organizationalStructure->endDate()->value();
        }

        if ($organizationalStructure->reason()->value() !== null) {
            $array['reason'] =
                $organizationalStructure->reason()->value();
        }

        if ($organizationalStructure->status()->value() !== null) {
            $array['status'] =
                $organizationalStructure->status()->value();
        }

        return $array;
    }
}
