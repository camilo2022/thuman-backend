<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\InterviewInitial\Contracts\InterviewInitialContract;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialApplicationId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialCreatedAt;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialRelationships;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialStatus;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialUpdatedAt;
use App\Domain\Vacant\Application\InterviewInitial\InterviewInitial;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialAnswers;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialDateTime;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialFormId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialIndication;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderObservation;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderScore;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderStatus;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialQuestions;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerObservation;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerScore;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerStatus;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialSalaryMax;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialSalaryMin;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialAllRequest;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialFindRequest;
use App\Models\Application as ApplicationModel;
use App\Models\InterviewInitial as InterviewInitialModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class InterviewInitialRepository implements InterviewInitialContract
{
    private $interviewInitialModel;

    public function __construct()
    {
        $this->interviewInitialModel = new InterviewInitialModel;
    }

    public function all(string|null $search = null, int|null $perPage, InterviewInitialAllRequest $request): LengthAwarePaginator
    {
        try {
            $interviewInitials = $this->interviewInitialModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'reviewer', 'leader',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country'
                ])
                ->whereHas('application', fn($r) => $r->whereIn('status', [ApplicationModel::REVIEW]))
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->orderBy('id', 'asc');

            return $interviewInitials->paginate($perPage ?? $interviewInitials->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los registros de las entrevistas iniciales: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los registros de las entrevistas iniciales: ' . $e->getMessage());
        }
    }

    public function find(InterviewInitialFindRequest $request, InterviewInitialId $interviewInitialId): ?InterviewInitial
    {
        try {
            $model = $this->interviewInitialModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'reviewer', 'leader',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country'
                ])
                ->when($interviewInitialId->value(), function ($query) use ($interviewInitialId) {
                    $query->where('id', $interviewInitialId->value());
                })
                ->when(!$interviewInitialId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de entrevista inicial no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro de entrevista inicial: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro de entrevista inicial: ' . $e->getMessage());
        }
    }

    public function store(InterviewInitial $interviewInitial): InterviewInitial
    {
        try {
            $model = $this->interviewInitialModel
                ->create($this->mapToModelData($interviewInitial));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro de entrevista inicial: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro de entrevista inicial: ' . $e->getMessage());
        }
    }

    public function update(InterviewInitial $vacant, InterviewInitialId $vacantId): InterviewInitial
    {
        try {
            $model = tap($this->interviewInitialModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de entrevista inicial no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro de entrevista inicial: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro de entrevista inicial: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): InterviewInitial
    {
        return new InterviewInitial(
            new InterviewInitialId($model->id),
            new InterviewInitialApplicationId($model->application?->id ?? $model->application_id),
            new InterviewInitialDateTime($model->date_time?->format('Y-m-d H:i:s')),
            new InterviewInitialFormId($model->form?->id ?? $model->form_id),
            new InterviewInitialQuestions($model->questions),
            new InterviewInitialAnswers($model->answers),
            new InterviewInitialSalaryMin($model->salary_min),
            new InterviewInitialSalaryMax($model->salary_max),
            new InterviewInitialReviewerObservation($model->reviewer_observation),
            new InterviewInitialReviewerScore($model->reviewer_score),
            new InterviewInitialReviewerId($model->reviewer_id),
            new InterviewInitialReviewerStatus($model->reviewer_status),
            new InterviewInitialLeaderObservation($model->leader_observation),
            new InterviewInitialLeaderScore($model->leader_score),
            new InterviewInitialLeaderId($model->leader_id),
            new InterviewInitialLeaderStatus($model->leader_status),
            new InterviewInitialIndication($model->indication),
            new InterviewInitialStatus($model->status),
            new InterviewInitialCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new InterviewInitialUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new InterviewInitialRelationships($model->application, $model->form, $model->reviewer, $model->leader)
        );
    }

    private function mapToModelData(InterviewInitial $interviewInitial): array
    {
        $array = [];

        if ($interviewInitial->applicationId()->value()) {
            $array['application_id'] = $interviewInitial->applicationId()->value();
        }

        if ($interviewInitial->dateTime()->value()) {
            $array['date_time'] = $interviewInitial->dateTime()->value();
        }

        if ($interviewInitial->formId()->value()) {
            $array['form_id'] = $interviewInitial->formId()->value();
        }

        if ($interviewInitial->questions()->value()) {
            $array['questions'] = $interviewInitial->questions()->value();
        }

        if ($interviewInitial->answers()->value()) {
            $array['answers'] = $interviewInitial->answers()->value();
        }

        if ($interviewInitial->salaryMin()->value()) {
            $array['salary_min'] = $interviewInitial->salaryMin()->value();
        }

        if ($interviewInitial->salaryMax()->value()) {
            $array['salary_max'] = $interviewInitial->salaryMax()->value();
        }

        if ($interviewInitial->reviewerObservation()->value()) {
            $array['reviewer_observation'] = $interviewInitial->reviewerObservation()->value();
        }

        if ($interviewInitial->reviewerScore()->value()) {
            $array['reviewer_score'] = $interviewInitial->reviewerScore()->value();
        }

        if ($interviewInitial->reviewerId()->value()) {
            $array['reviewer_id'] = $interviewInitial->reviewerId()->value();
        }

        if ($interviewInitial->reviewerStatus()->value()) {
            $array['reviewer_status'] = $interviewInitial->reviewerStatus()->value();
        }

        if ($interviewInitial->leaderObservation()->value()) {
            $array['leader_observation'] = $interviewInitial->leaderObservation()->value();
        }

        if ($interviewInitial->leaderScore()->value()) {
            $array['leader_score'] = $interviewInitial->leaderScore()->value();
        }

        if ($interviewInitial->leaderId()->value()) {
            $array['leader_id'] = $interviewInitial->leaderId()->value();
        }

        if ($interviewInitial->leaderStatus()->value()) {
            $array['leader_status'] = $interviewInitial->leaderStatus()->value();
        }

        if ($interviewInitial->indication()->value()) {
            $array['indication'] = $interviewInitial->indication()->value();
        }

        if ($interviewInitial->status()->value()) {
            $array['status'] = $interviewInitial->status()->value();
        }

        return $array;
    }
}
