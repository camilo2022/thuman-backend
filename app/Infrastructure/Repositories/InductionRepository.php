<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Induction\Contracts\InductionContract;
use App\Models\Induction as InductionModel;
use App\Domain\Induction\Induction;
use App\Domain\Induction\Values\InductionAssignableId;
use App\Domain\Induction\Values\InductionAssignableType;
use App\Domain\Induction\Values\InductionCreatedAt;
use App\Domain\Induction\Values\InductionDeletedAt;
use App\Domain\Induction\Values\InductionId;
use App\Domain\Induction\Values\InductionName;
use App\Domain\Induction\Values\InductionDescription;
use App\Domain\Induction\Values\InductionModelId;
use App\Domain\Induction\Values\InductionModelType;
use App\Domain\Induction\Values\InductionRelationships;
use App\Domain\Induction\Values\InductionUpdatedAt;
use App\Http\Requests\Induction\InductionAllRequest;
use App\Models\Enterprise as EnterpriseModel;
use App\Models\Entity as EntityModel;
use App\Models\Headquarter as HeadquarterModel;
use App\Models\Area as AreaModel;
use App\Models\Position as PositionModel;
use App\Models\Progress as ProgressModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class InductionRepository implements InductionContract
{
    private $inductionModel;

    public function __construct()
    {
        $this->inductionModel = new InductionModel;
    }

    public function all(string|null $search = null, int|null $perPage, InductionAllRequest $request): LengthAwarePaginator
    {
        try {
            $inductions = $this->inductionModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with([
                    'assignable' => fn (MorphTo $morphTo) => $morphTo->morphWith([
                        EnterpriseModel::class => [],
                        EntityModel::class => ['entity_type', 'enterprise'],
                        HeadquarterModel::class => ['entity.entity_type', 'entity.enterprise'],
                    ]),
                    'model' => fn (MorphTo $morphTo) => $morphTo->morphWith([
                        AreaModel::class => [],
                        PositionModel::class => ['area'],
                    ])
                ])
                ->withCount([
                    'steps as steps_total',
                    'steps as steps_finished' => function ($query) use ($request) {
                        $query->whereHas('progress', function ($q) use ($request) {
                            $q->where('status', ProgressModel::FINISHED)
                            ->where('result', ProgressModel::APPROVED)
                            ->when($request->filled('person_id'), fn ($sq) => $sq->where('person_id', $request->integer('person_id')));
                        });
                    }
                ])
                ->when($request->filled('person_id'), function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->where(function ($sq) {
                            $sq->whereDoesntHave('model')
                            ->whereDoesntHave('assignable');
                        })
                        ->orWhere(function ($sq) use ($request) {
                            $sq->whereHasMorph('model', [AreaModel::class, PositionModel::class], fn ($modelQuery, $modelType) =>
                                $modelQuery->when($modelType === AreaModel::class, fn ($q) => $q->whereHas('positions.employees', fn ($q) => $q->where('person_id', $request->integer('person_id'))))
                                    ->when($modelType === PositionModel::class, fn ($q) => $q->whereHas('employees', fn ($q) => $q->where('person_id', $request->integer('person_id')))))
                                ->orWhereHasMorph('assignable', [EnterpriseModel::class, EntityModel::class, HeadquarterModel::class], fn ($assignableQuery, $assignableType) =>
                                    $assignableQuery->when($assignableType === EnterpriseModel::class, fn ($q) => $q->whereHas('entities.headquarters.employees', fn ($q) => $q->where('person_id', $request->integer('person_id'))))
                                        ->when($assignableType === EntityModel::class, fn ($q) => $q->whereHas('headquarters.employees', fn ($q) => $q->where('person_id', $request->integer('person_id'))))
                                        ->when($assignableType === HeadquarterModel::class, fn ($q) => $q->whereHas('employees', fn ($q) => $q->where('person_id', $request->integer('person_id')))));
                        });
                    });
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $inductions->paginate($perPage ?? $inductions->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las inducciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las inducciones: ' . $e->getMessage());
        }
    }

    public function find(InductionId $inductionId): ?Induction
    {
        try {
            $model = $this->inductionModel->withTrashed()
                ->with([
                    'assignable' => fn (MorphTo $morphTo) => $morphTo->morphWith([
                        EnterpriseModel::class => [],
                        EntityModel::class => ['entity_type', 'enterprise'],
                        HeadquarterModel::class => ['entity.entity_type', 'entity.enterprise'],
                    ]),
                    'model' => fn (MorphTo $morphTo) => $morphTo->morphWith([
                        AreaModel::class => [],
                        PositionModel::class => ['area'],
                    ])
                ])
                ->findOrFail($inductionId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La inducción no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la inducción: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la inducción: ' . $e->getMessage());
        }
    }

    public function store(Induction $induction): Induction
    {
        try {
            $model = $this->inductionModel
                ->create($this->mapToModelData($induction));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la inducción: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la inducción: ' . $e->getMessage());
        }
    }

    public function update(Induction $induction, InductionId $inductionId): Induction
    {
        try {
            $model = tap($this->inductionModel->findOrFail($inductionId->value()))
                ->update($this->mapToModelData($induction))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La inducción no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la inducción: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la inducción: ' . $e->getMessage());
        }
    }

    public function delete(InductionId $inductionId): Induction
    {
        try {
            $model = tap($this->inductionModel->findOrFail($inductionId->value()), fn($induction) => $induction->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La inducción no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la inducción: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la inducción: ' . $e->getMessage());
        }
    }

    public function restore(InductionId $inductionId): Induction
    {
        try {
            $model = tap($this->inductionModel->withTrashed()->findOrFail($inductionId->value()), fn($induction) => $induction->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La inducción no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la inducción: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la inducción: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Induction
    {
        return new Induction(
            new InductionId($model->id),
            new InductionName($model->name),
            new InductionDescription($model->description),
            new InductionAssignableId($model->assignable_id),
            new InductionAssignableType($model->assignable_type),
            new InductionModelId($model->model_id),
            new InductionModelType($model->model_type),
            new InductionCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new InductionUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new InductionDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new InductionRelationships($model->assignable, $model->model)
        );
    }

    private function mapToModelData(Induction $induction): array
    {
        $array = [];

        if ($induction->name()->value()) {
            $array['name'] = $induction->name()->value();
        }

        if ($induction->description()->value()) {
            $array['description'] = $induction->description()->value();
        }

        $array['assignable_id'] = $induction->assignableId()->value();

        $array['assignable_type'] = $induction->assignableType()->value();

        $array['model_id'] = $induction->modelId()->value();

        $array['model_type'] = $induction->modelType()->value();

        return $array;
    }
}
