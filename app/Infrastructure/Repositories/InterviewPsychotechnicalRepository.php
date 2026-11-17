<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\InterviewPsychotechnical\Contracts\InterviewPsychotechnicalContract;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalApplicationId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalCreatedAt;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalRelationships;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalStatus;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalUpdatedAt;
use App\Domain\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnical;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalAnswers;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalDateTime;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalIndication;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistObservation;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistScore;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistStatus;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalQuestions;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalAllRequest;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalFindRequest;
use App\Models\Application as ApplicationModel;
use App\Models\InterviewPsychotechnical as InterviewPsychotechnicalModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class InterviewPsychotechnicalRepository implements InterviewPsychotechnicalContract
{
    private $interviewPsychotechnicalModel;

    public function __construct()
    {
        $this->interviewPsychotechnicalModel = new InterviewPsychotechnicalModel;
    }

    public function all(string|null $search = null, int|null $perPage, InterviewPsychotechnicalAllRequest $request): LengthAwarePaginator
    {
        try {
            $interviewPsychotechnicals = $this->interviewPsychotechnicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'psychologist',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country'
                ])
                ->whereHas('application', fn($r) => $r->whereIn('status', [ApplicationModel::REVIEW]))
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->orderBy('id', 'asc');

            return $interviewPsychotechnicals->paginate($perPage ?? $interviewPsychotechnicals->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los registros de las entrevistas psicológicas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los registros de las entrevistas psicológicas: ' . $e->getMessage());
        }
    }

    public function find(InterviewPsychotechnicalFindRequest $request, InterviewPsychotechnicalId $interviewPsychotechnicalId): ?InterviewPsychotechnical
    {
        try {
            $model = $this->interviewPsychotechnicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'psychologist',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country'
                ])
                ->when($interviewPsychotechnicalId->value(), function ($query) use ($interviewPsychotechnicalId) {
                    $query->where('id', $interviewPsychotechnicalId->value());
                })
                ->when(!$interviewPsychotechnicalId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de entrevista psicológica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro de entrevista psicológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro de entrevista psicológica: ' . $e->getMessage());
        }
    }

    public function store(InterviewPsychotechnical $interviewPsychotechnical): InterviewPsychotechnical
    {
        try {
            $model = $this->interviewPsychotechnicalModel
                ->create($this->mapToModelData($interviewPsychotechnical));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro de entrevista psicológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro de entrevista psicológica: ' . $e->getMessage());
        }
    }

    public function update(InterviewPsychotechnical $vacant, InterviewPsychotechnicalId $vacantId): InterviewPsychotechnical
    {
        try {
            $model = tap($this->interviewPsychotechnicalModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de entrevista psicológica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro de entrevista psicológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro de entrevista psicológica: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): InterviewPsychotechnical
    {
        return new InterviewPsychotechnical(
            new InterviewPsychotechnicalId($model->id),
            new InterviewPsychotechnicalApplicationId($model->application?->id ?? $model->application_id),
            new InterviewPsychotechnicalDateTime($model->date_time?->format('Y-m-d H:i:s')),
            new InterviewPsychotechnicalQuestions($model->questions),
            new InterviewPsychotechnicalAnswers($model->answers),
            new InterviewPsychotechnicalPsychologistObservation($model->psychologist_observation),
            new InterviewPsychotechnicalPsychologistScore($model->psychologist_score),
            new InterviewPsychotechnicalPsychologistId($model->psychologist_id),
            new InterviewPsychotechnicalPsychologistStatus($model->psychologist_status),
            new InterviewPsychotechnicalIndication($model->indication),
            new InterviewPsychotechnicalStatus($model->status),
            new InterviewPsychotechnicalCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new InterviewPsychotechnicalUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new InterviewPsychotechnicalRelationships($model->application, $model->psychologist)
        );
    }

    private function mapToModelData(InterviewPsychotechnical $interviewPsychotechnical): array
    {
        $array = [];

        if ($interviewPsychotechnical->applicationId()->value()) {
            $array['application_id'] = $interviewPsychotechnical->applicationId()->value();
        }

        if ($interviewPsychotechnical->dateTime()->value()) {
            $array['date_time'] = $interviewPsychotechnical->dateTime()->value();
        }

        if ($interviewPsychotechnical->questions()->value()) {
            $array['questions'] = $interviewPsychotechnical->questions()->value();
        }

        if ($interviewPsychotechnical->answers()->value()) {
            $array['answers'] = $interviewPsychotechnical->answers()->value();
        }

        if ($interviewPsychotechnical->psychologistObservation()->value()) {
            $array['psychologist_observation'] = $interviewPsychotechnical->psychologistObservation()->value();
        }

        if ($interviewPsychotechnical->psychologistScore()->value()) {
            $array['psychologist_score'] = $interviewPsychotechnical->psychologistScore()->value();
        }

        if ($interviewPsychotechnical->psychologistId()->value()) {
            $array['psychologist_id'] = $interviewPsychotechnical->psychologistId()->value();
        }

        if ($interviewPsychotechnical->psychologistStatus()->value()) {
            $array['psychologist_status'] = $interviewPsychotechnical->psychologistStatus()->value();
        }

        if ($interviewPsychotechnical->indication()->value()) {
            $array['indication'] = $interviewPsychotechnical->indication()->value();
        }

        if ($interviewPsychotechnical->status()->value()) {
            $array['status'] = $interviewPsychotechnical->status()->value();
        }

        return $array;
    }
}
