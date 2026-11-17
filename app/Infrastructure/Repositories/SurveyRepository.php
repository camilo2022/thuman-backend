<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Survey\Contracts\SurveyContract;
use App\Models\Survey as SurveyModel;
use App\Domain\Survey\Survey;
use App\Domain\Survey\Values\SurveyAnswers;
use App\Domain\Survey\Values\SurveyCreatedAt;
use App\Domain\Survey\Values\SurveyId;
use App\Domain\Survey\Values\SurveyPersonId;
use App\Domain\Survey\Values\SurveyFormId;
use App\Domain\Survey\Values\SurveyQuestions;
use App\Domain\Survey\Values\SurveyRelationships;
use App\Domain\Survey\Values\SurveyUpdatedAt;
use App\Http\Requests\Survey\SurveyAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SurveyRepository implements SurveyContract
{
    private $formModel;

    public function __construct()
    {
        $this->formModel = new SurveyModel;
    }

    public function all(string|null $search = null, int|null $perPage, SurveyAllRequest $request): LengthAwarePaginator
    {
        try {
            $surveys = $this->formModel
                ->with([
                    'form.form_type',
                    'form.assignable',
                    'form.model',
                    'person.city.departament.country'
                ])
                ->when($request->filled('form_id'), function ($q) use ($request) {
                    $q->where('form_id', $request->integer('form_id'));
                })
                ->when($request->filled('form_type_id'), function ($q) use ($request) {
                    $q->whereHas('form', function ($sq) use ($request) {
                        $sq->where('form_type_id', $request->integer('form_type_id'));
                    });
                })
                ->when(
                    $request->filled('assignable_type') && $request->filled('assignable_id'),
                    function ($q) use ($request) {
                        $assignableType = $request->input('assignable_type');
                        $assignableId = $request->integer('assignable_id');

                        $q->whereHas('form', function ($sq) use ($assignableType, $assignableId) {
                            $sq->where(function ($formQuery) use ($assignableType, $assignableId) {
                                $formQuery
                                    ->where(function ($exact) use ($assignableType, $assignableId) {
                                        $exact->where('assignable_type', $assignableType)
                                            ->where('assignable_id', $assignableId);
                                    })
                                    ->orWhere(function ($global) {
                                        $global->whereNull('assignable_type')
                                            ->whereNull('assignable_id');
                                    });
                            });
                        });
                    }
                )
                ->when(
                    $request->filled('assignable_type') && !$request->filled('assignable_id'),
                    function ($q) use ($request) {
                        $assignableType = $request->input('assignable_type');

                        $q->whereHas('form', function ($sq) use ($assignableType) {
                            $sq->where(function ($formQuery) use ($assignableType) {
                                $formQuery
                                    ->where('assignable_type', $assignableType)
                                    ->orWhere(function ($global) {
                                        $global->whereNull('assignable_type')
                                            ->whereNull('assignable_id');
                                    });
                            });
                        });
                    }
                )
                ->when(
                    $request->filled('model_type') && $request->filled('model_id'),
                    function ($q) use ($request) {
                        $modelType = $request->input('model_type');
                        $modelId = $request->integer('model_id');

                        $q->whereHas('form', function ($sq) use ($modelType, $modelId) {
                            $sq->where(function ($formQuery) use ($modelType, $modelId) {
                                $formQuery
                                    ->where(function ($exact) use ($modelType, $modelId) {
                                        $exact->where('model_type', $modelType)
                                            ->where('model_id', $modelId);
                                    })
                                    ->orWhere(function ($global) {
                                        $global->whereNull('model_type')
                                            ->whereNull('model_id');
                                    });
                            });
                        });
                    }
                )
                ->when(
                    $request->filled('model_type') && !$request->filled('model_id'),
                    function ($q) use ($request) {
                        $modelType = $request->input('model_type');

                        $q->whereHas('form', function ($sq) use ($modelType) {
                            $sq->where(function ($formQuery) use ($modelType) {
                                $formQuery
                                    ->where('model_type', $modelType)
                                    ->orWhere(function ($global) {
                                        $global->whereNull('model_type')
                                            ->whereNull('model_id');
                                    });
                            });
                        });
                    }
                )

                ->when($request->filled('person_id'), function ($q) use ($request) {
                    $q->where('person_id', $request->integer('person_id'));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'desc');

            return $surveys->paginate($perPage ?? $surveys->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las encuestas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las encuestas: ' . $e->getMessage());
        }
    }

    public function find(SurveyId $formId): ?Survey
    {
        try {
            $model = $this->formModel
                ->with(['form.entity', 'form.position', 'person'])
                ->findOrFail($formId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La encuesta no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la encuesta: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la encuesta: ' . $e->getMessage());
        }
    }

    public function store(Survey $form): Survey
    {
        try {
            $model = $this->formModel
                ->create($this->mapToModelData($form));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la encuesta: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la encuesta: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Survey
    {
        return new Survey(
            new SurveyId($model->id),
            new SurveyFormId($model->form_id),
            new SurveyQuestions($model->questions),
            new SurveyAnswers($model->answers),
            new SurveyPersonId($model->person_id),
            new SurveyCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SurveyUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SurveyRelationships($model->form, $model->person)
        );
    }

    private function mapToModelData(Survey $form): array
    {
        $array = [];

        if ($form->formId()->value()) {
            $array['form_id'] = $form->formId()->value();
        }

        if ($form->questions()->value()) {
            $array['questions'] = $form->questions()->value();
        }

        if ($form->answers()->value()) {
            $array['answers'] = $form->answers()->value();
        }

        if ($form->personId()->value()) {
            $array['person_id'] = $form->personId()->value();
        }

        return $array;
    }
}
