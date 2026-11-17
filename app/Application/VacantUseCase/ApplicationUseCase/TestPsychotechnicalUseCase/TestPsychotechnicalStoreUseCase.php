<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\TestPsychotechnicalUseCase;

use App\Domain\Vacant\Application\TestPsychotechnical\Contracts\TestPsychotechnicalContract;
use App\Domain\Vacant\Application\TestPsychotechnical\TestPsychotechnical;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalAnswers;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalApplicationId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalEndAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalEndDate;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalFormId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalIndication;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistObservation;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistStatus;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalQuestions;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStartAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStartDate;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStatus;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalStoreRequest;
use App\Models\TestPsychotechnical as TestPsychotechnicalModel;

final class TestPsychotechnicalStoreUseCase
{
    private $testPsychotechnicalContract;

    public function __construct(TestPsychotechnicalContract $testPsychotechnicalContract)
    {
        $this->testPsychotechnicalContract = $testPsychotechnicalContract;
    }

    public function __invoke(TestPsychotechnicalStoreRequest $request): TestPsychotechnical
    {
        return $this->testPsychotechnicalContract->store(
            new TestPsychotechnical(
                new TestPsychotechnicalId(null),
                new TestPsychotechnicalApplicationId($request->integer('application_id')),
                new TestPsychotechnicalStartDate($request->input('start_date')),
                new TestPsychotechnicalEndDate($request->input('end_date')),
                new TestPsychotechnicalFormId($request->integer('form_id')),
                new TestPsychotechnicalQuestions($request->input('questions')),
                new TestPsychotechnicalAnswers($request->input('answers')),
                new TestPsychotechnicalPsychologistObservation($request->input('psychologist_observation')),
                new TestPsychotechnicalPsychologistId($request->integer('psychologist_id')),
                new TestPsychotechnicalPsychologistStatus(TestPsychotechnicalModel::PENDING),
                new TestPsychotechnicalStartAt($request->input('start_at')),
                new TestPsychotechnicalEndAt($request->input('end_at')),
                new TestPsychotechnicalIndication($request->input('indication')),
                new TestPsychotechnicalStatus(TestPsychotechnicalModel::PENDING)
            )
        );
    }
}
