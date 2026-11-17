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
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalStartRequest;
use App\Models\TestPsychotechnical as TestPsychotechnicalModel;
use Carbon\Carbon;

final class TestPsychotechnicalStartUseCase
{
    private $testPsychotechnicalContract;

    public function __construct(TestPsychotechnicalContract $testPsychotechnicalContract)
    {
        $this->testPsychotechnicalContract = $testPsychotechnicalContract;
    }

    public function __invoke(TestPsychotechnicalStartRequest $request, TestPsychotechnicalId $testPsychotechnicalId): TestPsychotechnical
    {
        return $this->testPsychotechnicalContract->update(
            new TestPsychotechnical(
                new TestPsychotechnicalId($testPsychotechnicalId->value()),
                new TestPsychotechnicalApplicationId($request->integer('application_id')),
                new TestPsychotechnicalStartDate($request->input('start_date')),
                new TestPsychotechnicalEndDate($request->input('end_date')),
                new TestPsychotechnicalFormId($request->integer('form_id')),
                new TestPsychotechnicalQuestions($request->input('questions')),
                new TestPsychotechnicalAnswers($request->input('answers')),
                new TestPsychotechnicalPsychologistObservation($request->input('psychologist_observation')),
                new TestPsychotechnicalPsychologistId($request->integer('psychologist_id')),
                new TestPsychotechnicalPsychologistStatus($request->input('psychologist_status')),
                new TestPsychotechnicalStartAt(Carbon::now()->format('Y-m-d H:i:s')),
                new TestPsychotechnicalEndAt($request->input('end_at')),
                new TestPsychotechnicalIndication($request->input('indication')),
                new TestPsychotechnicalStatus(TestPsychotechnicalModel::INPROGRESS)
            ),
            $testPsychotechnicalId
        );
    }
}
