<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewVideoUseCase;

use App\Domain\Vacant\Application\InterviewVideo\Contracts\InterviewVideoContract;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoAnswers;
use App\Domain\Vacant\Application\InterviewVideo\InterviewVideo;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoApplicationId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoFormId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoQuestions;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoReviewerId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoReviewerObservation;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoStatus;
use App\Http\Requests\Vacant\Application\InterviewVideo\InterviewVideoUpdateRequest;

final class InterviewVideoUpdateUseCase
{
    private $interviewVideoContract;

    public function __construct(InterviewVideoContract $interviewVideoContract)
    {
        $this->interviewVideoContract = $interviewVideoContract;
    }

    public function __invoke(InterviewVideoUpdateRequest $request, InterviewVideoId $interviewVideoId): InterviewVideo
    {
        return $this->interviewVideoContract->update(
            new InterviewVideo(
                new InterviewVideoId($interviewVideoId->value()),
                new InterviewVideoApplicationId($request->integer('application_id')),
                new InterviewVideoFormId($request->integer('form_id')),
                new InterviewVideoQuestions($request->input('questions')),
                new InterviewVideoAnswers($request->input('answers')),
                new InterviewVideoReviewerObservation($request->input('reviewer_observation')),
                new InterviewVideoReviewerId($request->integer('reviewer_id')),
                new InterviewVideoStatus($request->input('status'))
            ),
            $interviewVideoId
        );
    }
}
