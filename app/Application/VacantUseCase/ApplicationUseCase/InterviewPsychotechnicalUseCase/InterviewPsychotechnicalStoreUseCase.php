<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewPsychotechnicalUseCase;

use App\Domain\Vacant\Application\InterviewPsychotechnical\Contracts\InterviewPsychotechnicalContract;
use App\Domain\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnical;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalAnswers;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalApplicationId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalDateTime;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalIndication;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalQuestions;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistObservation;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistScore;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistStatus;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalStatus;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalStoreRequest;
use App\Models\InterviewPsychotechnical as InterviewPsychotechnicalModel;

final class InterviewPsychotechnicalStoreUseCase
{
    private $interviewPsychotechnicalContract;

    public function __construct(InterviewPsychotechnicalContract $interviewPsychotechnicalContract)
    {
        $this->interviewPsychotechnicalContract = $interviewPsychotechnicalContract;
    }

    public function __invoke(InterviewPsychotechnicalStoreRequest $request): InterviewPsychotechnical
    {
        return $this->interviewPsychotechnicalContract->store(
            new InterviewPsychotechnical(
                new InterviewPsychotechnicalId(null),
                new InterviewPsychotechnicalApplicationId($request->integer('application_id')),
                new InterviewPsychotechnicalDateTime($request->input('date_time')),
                new InterviewPsychotechnicalQuestions($request->input('questions')),
                new InterviewPsychotechnicalAnswers($request->input('answers')),
                new InterviewPsychotechnicalPsychologistObservation($request->input('psychologist_observation')),
                new InterviewPsychotechnicalPsychologistScore($request->input('psychologist_score')),
                new InterviewPsychotechnicalPsychologistId($request->integer('psychologist_id')),
                new InterviewPsychotechnicalPsychologistStatus(InterviewPsychotechnicalModel::PENDING),
                new InterviewPsychotechnicalIndication($request->input('indication')),
                new InterviewPsychotechnicalStatus(InterviewPsychotechnicalModel::SCHEDULED)
            )
        );
    }
}
