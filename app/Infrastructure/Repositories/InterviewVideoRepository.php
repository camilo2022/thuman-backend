<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\InterviewVideo\Contracts\InterviewVideoContract;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoAnswers;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoApplicationId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoCreatedAt;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoFormId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoQuestions;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoRelationships;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoReviewerId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoReviewerObservation;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoStatus;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoUpdatedAt;
use App\Domain\Vacant\Application\InterviewVideo\InterviewVideo;
use App\Http\Requests\Vacant\Application\InterviewVideo\InterviewVideoFindRequest;
use App\Models\InterviewVideo as InterviewVideoModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class InterviewVideoRepository implements InterviewVideoContract
{
    private $interviewVideoModel;

    public function __construct()
    {
        $this->interviewVideoModel = new InterviewVideoModel;
    }

    public function find(InterviewVideoFindRequest $request, InterviewVideoId $interviewVideoId): ?InterviewVideo
    {
        try {
            $model = $this->interviewVideoModel
                ->with(['application', 'form', 'reviewer'])
                ->when($interviewVideoId->value(), function ($query) use ($interviewVideoId) {
                    $query->where('id', $interviewVideoId->value());
                })
                ->when(!$interviewVideoId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El video entrevista no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el video entrevista: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el video entrevista: ' . $e->getMessage());
        }
    }

    public function store(InterviewVideo $interviewVideo): InterviewVideo
    {
        try {
            $model = $this->interviewVideoModel
                ->create($this->mapToModelData($interviewVideo));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el video entrevista: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el video entrevista: ' . $e->getMessage());
        }
    }

    public function update(InterviewVideo $interviewVideo, InterviewVideoId $interviewVideoId): InterviewVideo
    {
        try {
            $model = tap($this->interviewVideoModel->findOrFail($interviewVideoId->value()))
                ->update($this->mapToModelData($interviewVideo))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La video entrevista no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el video entrevista: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el video entrevista: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): InterviewVideo
    {
        return new InterviewVideo(
            new InterviewVideoId($model->id),
            new InterviewVideoApplicationId($model->application?->id ?? $model->application_id),
            new InterviewVideoFormId($model->form?->id ?? $model->form_id),
            new InterviewVideoQuestions($model->questions),
            new InterviewVideoAnswers($model->answers),
            new InterviewVideoReviewerObservation($model->reviewer_observation),
            new InterviewVideoReviewerId($model->reviewer_id),
            new InterviewVideoStatus($model->status),
            new InterviewVideoCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new InterviewVideoUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new InterviewVideoRelationships($model->application, $model->form, $model->reviewer)
        );
    }

    private function mapToModelData(InterviewVideo $interviewVideo): array
    {
        $array = [];

        if ($interviewVideo->applicationId()->value()) {
            $array['application_id'] = $interviewVideo->applicationId()->value();
        }

        if ($interviewVideo->formId()->value()) {
            $array['form_id'] = $interviewVideo->formId()->value();
        }

        if ($interviewVideo->questions()->value()) {
            $array['questions'] = $interviewVideo->questions()->value();
        }

        if ($interviewVideo->answers()->value()) {
            $array['answers'] = $interviewVideo->answers()->value();
        }

        if ($interviewVideo->reviewerObservation()->value()) {
            $array['reviewer_observation'] = $interviewVideo->reviewerObservation()->value();
        }

        if ($interviewVideo->reviewerId()->value()) {
            $array['reviewer_id'] = $interviewVideo->reviewerId()->value();
        }

        if ($interviewVideo->status()->value()) {
            $array['status'] = $interviewVideo->status()->value();
        }

        return $array;
    }
}
