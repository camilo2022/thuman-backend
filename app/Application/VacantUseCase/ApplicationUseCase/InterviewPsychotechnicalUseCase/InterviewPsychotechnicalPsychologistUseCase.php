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
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalPsychologistRequest;

final class InterviewPsychotechnicalPsychologistUseCase
{
    private $interviewPsychotechnicalContract;

    public function __construct(InterviewPsychotechnicalContract $interviewPsychotechnicalContract)
    {
        $this->interviewPsychotechnicalContract = $interviewPsychotechnicalContract;
    }

    public function __invoke(InterviewPsychotechnicalPsychologistRequest $request, InterviewPsychotechnicalId $interviewPsychotechnicalId): InterviewPsychotechnical
    {
        return $this->interviewPsychotechnicalContract->update(
            new InterviewPsychotechnical(
                new InterviewPsychotechnicalId($interviewPsychotechnicalId->value()),
                new InterviewPsychotechnicalApplicationId($request->integer('application_id')),
                new InterviewPsychotechnicalDateTime($request->input('date_time')),
                new InterviewPsychotechnicalQuestions($request->input('questions')),
                new InterviewPsychotechnicalAnswers($request->input('answers')),
                new InterviewPsychotechnicalPsychologistObservation($request->input('psychologist_observation')),
                new InterviewPsychotechnicalPsychologistScore($request->input('psychologist_score')),
                new InterviewPsychotechnicalPsychologistId($request->integer('psychologist_id')),
                new InterviewPsychotechnicalPsychologistStatus($request->input('psychologist_status')),
                new InterviewPsychotechnicalIndication($request->input('indication')),
                new InterviewPsychotechnicalStatus($request->input('status'))
            ),
            $interviewPsychotechnicalId
        );
    }
}
