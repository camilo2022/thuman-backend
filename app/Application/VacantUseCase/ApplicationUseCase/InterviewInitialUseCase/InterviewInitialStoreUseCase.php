<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase;

use App\Domain\Vacant\Application\InterviewInitial\Contracts\InterviewInitialContract;
use App\Domain\Vacant\Application\InterviewInitial\InterviewInitial;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialAnswers;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialApplicationId;
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
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialStatus;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialStoreRequest;
use App\Models\InterviewInitial as InterviewInitialModel;

final class InterviewInitialStoreUseCase
{
    private $interviewInitialContract;

    public function __construct(InterviewInitialContract $interviewInitialContract)
    {
        $this->interviewInitialContract = $interviewInitialContract;
    }

    public function __invoke(InterviewInitialStoreRequest $request): InterviewInitial
    {
        return $this->interviewInitialContract->store(
            new InterviewInitial(
                new InterviewInitialId(null),
                new InterviewInitialApplicationId($request->integer('application_id')),
                new InterviewInitialDateTime($request->input('date_time')),
                new InterviewInitialFormId($request->integer('form_id')),
                new InterviewInitialQuestions($request->input('questions')),
                new InterviewInitialAnswers($request->input('answers')),
                new InterviewInitialSalaryMin((float) $request->get('salary_min')),
                new InterviewInitialSalaryMax((float) $request->get('salary_max')),
                new InterviewInitialReviewerObservation($request->input('reviewer_observation')),
                new InterviewInitialReviewerScore($request->input('reviewer_score')),
                new InterviewInitialReviewerId($request->integer('reviewer_id')),
                new InterviewInitialReviewerStatus(InterviewInitialModel::PENDING),
                new InterviewInitialLeaderObservation($request->input('leader_observation')),
                new InterviewInitialLeaderScore($request->input('leader_score')),
                new InterviewInitialLeaderId($request->integer('leader_id')),
                new InterviewInitialLeaderStatus(InterviewInitialModel::PENDING),
                new InterviewInitialIndication($request->input('indication')),
                new InterviewInitialStatus(InterviewInitialModel::SCHEDULED)
            )
        );
    }
}
