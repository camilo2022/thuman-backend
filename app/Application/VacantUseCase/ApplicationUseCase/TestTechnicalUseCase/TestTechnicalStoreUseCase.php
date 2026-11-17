<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\TestTechnicalUseCase;

use App\Domain\Vacant\Application\TestTechnical\Contracts\TestTechnicalContract;
use App\Domain\Vacant\Application\TestTechnical\TestTechnical;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalAnswers;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalApplicationId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalEndAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalEndDate;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalFormId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalIndication;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderObservation;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderStatus;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalQuestions;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStartAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStartDate;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStatus;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalStoreRequest;
use App\Models\TestTechnical as TestTechnicalModel;

final class TestTechnicalStoreUseCase
{
    private $testTechnicalContract;

    public function __construct(TestTechnicalContract $testTechnicalContract)
    {
        $this->testTechnicalContract = $testTechnicalContract;
    }

    public function __invoke(TestTechnicalStoreRequest $request): TestTechnical
    {
        return $this->testTechnicalContract->store(
            new TestTechnical(
                new TestTechnicalId(null),
                new TestTechnicalApplicationId($request->integer('application_id')),
                new TestTechnicalStartDate($request->input('start_date')),
                new TestTechnicalEndDate($request->input('end_date')),
                new TestTechnicalFormId($request->integer('form_id')),
                new TestTechnicalQuestions($request->input('questions')),
                new TestTechnicalAnswers($request->input('answers')),
                new TestTechnicalLeaderObservation($request->input('leader_observation')),
                new TestTechnicalLeaderId($request->integer('leader_id')),
                new TestTechnicalLeaderStatus(TestTechnicalModel::PENDING),
                new TestTechnicalStartAt($request->input('start_at')),
                new TestTechnicalEndAt($request->input('end_at')),
                new TestTechnicalIndication($request->input('indication')),
                new TestTechnicalStatus(TestTechnicalModel::PENDING)
            )
        );
    }
}
