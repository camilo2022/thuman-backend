<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Form\Contracts\FormContract;
use App\Models\Form as FormModel;
use App\Domain\Form\Form;
use App\Domain\Form\Values\FormAssignableId;
use App\Domain\Form\Values\FormAssignableType;
use App\Domain\Form\Values\FormCreatedAt;
use App\Domain\Form\Values\FormDeletedAt;
use App\Domain\Form\Values\FormId;
use App\Domain\Form\Values\FormEntityId;
use App\Domain\Form\Values\FormFormTypeId;
use App\Domain\Form\Values\FormModelId;
use App\Domain\Form\Values\FormModelType;
use App\Domain\Form\Values\FormPositionId;
use App\Domain\Form\Values\FormQuestions;
use App\Domain\Form\Values\FormRelationships;
use App\Domain\Form\Values\FormUpdatedAt;
use App\Http\Requests\Form\FormAllRequest;
use App\Http\Requests\Form\FormFindRequest;
use App\Models\Enterprise as EnterpriseModel;
use App\Models\Entity as EntityModel;
use App\Models\Headquarter as HeadquarterModel;
use App\Models\Area as AreaModel;
use App\Models\InterviewVideo;
use App\Models\Position as PositionModel;
use App\Models\Step;
use App\Models\TestPsychotechnical;
use App\Models\TestTechnical;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FormRepository implements FormContract
{
    private $formModel;

    public function __construct()
    {
        $this->formModel = new FormModel;
    }

    public function all(string|null $search = null, int|null $perPage, FormAllRequest $request): LengthAwarePaginator
    {
        try {
            $assignableCandidates = $this->buildAssignableCandidates($request);
            $modelCandidates = $this->buildModelCandidates($request);

            $forms = $this->formModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['form_type'])
                ->with([
                    'assignable' => fn(MorphTo $morphTo) => $morphTo->morphWith([
                        EnterpriseModel::class => [],
                        EntityModel::class => ['entity_type', 'enterprise'],
                        HeadquarterModel::class => ['entity.entity_type', 'entity.enterprise'],
                    ]),
                    'model' => fn(MorphTo $morphTo) => $morphTo->morphWith([
                        AreaModel::class => [],
                        PositionModel::class => ['area'],
                    ])
                ])
                ->when($request->filled('form_type_id'), function ($q) use ($request) {
                    $q->where('form_type_id', $request->input('form_type_id'));
                })
                ->when(!empty($assignableCandidates), function ($q) use ($assignableCandidates) {
                    $q->where(function ($sub) use ($assignableCandidates) {
                        foreach ($assignableCandidates as $candidate) {
                            $sub->orWhere(function ($item) use ($candidate) {
                                if (is_null($candidate['type']) && is_null($candidate['id'])) {
                                    $item->whereNull('assignable_type')
                                        ->whereNull('assignable_id');
                                } else {
                                    $item->where('assignable_type', $candidate['type'])
                                        ->where('assignable_id', $candidate['id']);
                                }
                            });
                        }
                    });
                })
                ->when(!empty($modelCandidates), function ($q) use ($modelCandidates) {
                    $q->where(function ($sub) use ($modelCandidates) {
                        foreach ($modelCandidates as $candidate) {
                            $sub->orWhere(function ($item) use ($candidate) {
                                if (is_null($candidate['type']) && is_null($candidate['id'])) {
                                    $item->whereNull('model_type')
                                        ->whereNull('model_id');
                                } else {
                                    $item->where('model_type', $candidate['type'])
                                        ->where('model_id', $candidate['id']);
                                }
                            });
                        }
                    });
                })
                ->when($request->filled('person_id'), function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->where(function ($sq) {
                            $sq->whereDoesntHave('model')
                                ->whereDoesntHave('assignable');
                        })->orWhere(function ($sq) use ($request) {
                            $sq->whereHasMorph(
                                'model',
                                [AreaModel::class, PositionModel::class],
                                fn($modelQuery, $modelType) =>
                                $modelQuery
                                    ->when(
                                        $modelType === AreaModel::class,
                                        fn($q) =>
                                        $q->whereHas(
                                            'positions.employees',
                                            fn($q) =>
                                            $q->where('person_id', $request->integer('person_id'))
                                        )
                                    )
                                    ->when(
                                        $modelType === PositionModel::class,
                                        fn($q) =>
                                        $q->whereHas(
                                            'employees',
                                            fn($q) =>
                                            $q->where('person_id', $request->integer('person_id'))
                                        )
                                    )
                            )->orWhereHasMorph(
                                'assignable',
                                [EnterpriseModel::class, EntityModel::class, HeadquarterModel::class],
                                fn($assignableQuery, $assignableType) =>
                                $assignableQuery
                                    ->when(
                                        $assignableType === EnterpriseModel::class,
                                        fn($q) =>
                                        $q->whereHas(
                                            'entities.headquarters.employees',
                                            fn($q) =>
                                            $q->where('person_id', $request->integer('person_id'))
                                        )
                                    )
                                    ->when(
                                        $assignableType === EntityModel::class,
                                        fn($q) =>
                                        $q->whereHas(
                                            'headquarters.employees',
                                            fn($q) =>
                                            $q->where('person_id', $request->integer('person_id'))
                                        )
                                    )
                                    ->when(
                                        $assignableType === HeadquarterModel::class,
                                        fn($q) =>
                                        $q->whereHas(
                                            'employees',
                                            fn($q) =>
                                            $q->where('person_id', $request->integer('person_id'))
                                        )
                                    )
                            );
                        });
                    });
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $forms->paginate($perPage ?? $forms->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los formularios: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los formularios: ' . $e->getMessage());
        }
    }

    public function find(FormFindRequest $request, FormId $formId): ?Form
    {
        try {
            $model = $this->formModel
                ->with([
                    'form_type',
                    'assignable' => fn(MorphTo $morphTo) => $morphTo->morphWith([
                        EnterpriseModel::class => [],
                        EntityModel::class => ['entity_type', 'enterprise'],
                        HeadquarterModel::class => ['entity.entity_type', 'entity.enterprise'],
                    ]),
                    'model' => fn(MorphTo $morphTo) => $morphTo->morphWith([
                        AreaModel::class => [],
                        PositionModel::class => ['area'],
                    ])
                ])
                ->when($formId->value(), function ($query) use ($formId) {
                    $query->where('id', $formId->value());
                })
                ->when(!$formId->value(), function ($query) use ($request) {
                    $query->when($request->filled('form_type_id'), function ($query) use ($request) {
                        $query->where('form_type_id', $request->input('form_type_id'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El formulario no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el formulario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el formulario: ' . $e->getMessage());
        }
    }

    public function store(Form $form): Form
    {
        try {
            $model = $this->formModel
                ->create($this->mapToModelData($form));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el formulario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el formulario: ' . $e->getMessage());
        }
    }

    public function update(Form $form, FormId $formId): Form
    {
        try {
            $model = tap($this->formModel->findOrFail($formId->value()))
                ->update($this->mapToModelData($form))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El formulario no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el formulario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el formulario: ' . $e->getMessage());
        }
    }

    public function delete(FormId $formId): Form
    {
        try {
            $model = $this->formModel->findOrFail($formId->value());

            $isUsed = InterviewVideo::where('form_id', $formId->value())->exists() || TestTechnical::where('form_id', $formId->value())->exists()
                || TestPsychotechnical::where('form_id', $formId->value())->exists() || Step::where('form_id', $formId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El formulario no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el formulario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el formulario: ' . $e->getMessage());
        }
    }

    public function restore(FormId $formId): Form
    {
        try {
            $model = tap($this->formModel->withTrashed()->findOrFail($formId->value()), fn($form) => $form->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El formulario no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el formulario: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el formulario: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Form
    {
        return new Form(
            new FormId($model->id),
            new FormFormTypeId($model->form_type_id),
            // new FormEntityId($model->entity_id),
            // new FormPositionId($model->position_id),
            new FormAssignableId($model->assignable_id),
            new FormAssignableType($model->assignable_type),
            new FormModelId($model->model_id),
            new FormModelType($model->model_type),
            new FormQuestions($model->questions),
            new FormCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FormUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FormDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new FormRelationships(
                $model->form_type,
                $model->assignable,
                $model->model
            )
        );
    }

    private function mapToModelData(Form $form): array
    {
        $array = [];

        if ($form->formTypeId()->value()) {
            $array['form_type_id'] = $form->formTypeId()->value();
        }

        // if ($form->entityId()->value()) {
        //     $array['entity_id'] = $form->entityId()->value();
        // }

        // if ($form->positionId()->value()) {
        //     $array['position_id'] = $form->positionId()->value();
        // }

        $array['assignable_id'] = $form->assignableId()->value();

        $array['assignable_type'] = $form->assignableType()->value();

        $array['model_id'] = $form->modelId()->value();

        $array['model_type'] = $form->modelType()->value();

        if ($form->questions()->value()) {
            $array['questions'] = $form->questions()->value();
        }

        return $array;
    }

    private function buildModelCandidates(FormAllRequest $request): array
    {
        $candidates = [];

        if (!$request->filled('model_type') || !$request->filled('model_id')) {
            return $candidates;
        }

        $modelType = $request->input('model_type');
        $modelId = (int) $request->input('model_id');

        // Exacto
        $candidates[] = [
            'type' => $modelType,
            'id' => $modelId,
        ];

        // Herencia Position -> Area
        if ($modelType === PositionModel::class) {
            $position = PositionModel::select('id', 'area_id')->find($modelId);

            if ($position?->area_id) {
                $candidates[] = [
                    'type' => AreaModel::class,
                    'id' => (int) $position->area_id,
                ];
            }
        }

        // Global
        $candidates[] = [
            'type' => null,
            'id' => null,
        ];

        return $candidates;
    }

    private function buildAssignableCandidates(FormAllRequest $request): array
    {
        $candidates = [];

        if (!$request->filled('assignable_type') || !$request->filled('assignable_id')) {
            return $candidates;
        }

        $assignableType = $request->input('assignable_type');
        $assignableId = (int) $request->input('assignable_id');

        // Exacto
        $candidates[] = [
            'type' => $assignableType,
            'id' => $assignableId,
        ];

        if ($assignableType === HeadquarterModel::class) {
            $headquarter = HeadquarterModel::with('entity:id,enterprise_id')->find($assignableId);

            if ($headquarter?->entity_id) {
                $candidates[] = [
                    'type' => EntityModel::class,
                    'id' => (int) $headquarter->entity_id,
                ];
            }

            if ($headquarter?->entity?->enterprise_id) {
                $candidates[] = [
                    'type' => EnterpriseModel::class,
                    'id' => (int) $headquarter->entity->enterprise_id,
                ];
            }
        }

        if ($assignableType === EntityModel::class) {
            $entity = EntityModel::select('id', 'enterprise_id')->find($assignableId);

            if ($entity?->enterprise_id) {
                $candidates[] = [
                    'type' => EnterpriseModel::class,
                    'id' => (int) $entity->enterprise_id,
                ];
            }
        }

        // Global
        $candidates[] = [
            'type' => null,
            'id' => null,
        ];

        return $candidates;
    }
}
