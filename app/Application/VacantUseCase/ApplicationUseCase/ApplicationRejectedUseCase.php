<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase;

use App\Domain\Vacant\Application\Contracts\ApplicationContract;
use App\Domain\Vacant\Application\Application;
use App\Domain\Vacant\Application\Values\ApplicationAnswers;
use App\Domain\Vacant\Application\Values\ApplicationFormId;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Domain\Vacant\Application\Values\ApplicationObservation;
use App\Domain\Vacant\Application\Values\ApplicationPersonId;
use App\Domain\Vacant\Application\Values\ApplicationQuestions;
use App\Domain\Vacant\Application\Values\ApplicationSalary;
use App\Domain\Vacant\Application\Values\ApplicationStatus;
use App\Domain\Vacant\Application\Values\ApplicationVacantId;
use App\Models\Application as ApplicationModel;
use App\Http\Requests\Vacant\Application\ApplicationRejectedRequest;

final class ApplicationRejectedUseCase
{
    private $applicationContract;

    public function __construct(ApplicationContract $applicationContract)
    {
        $this->applicationContract = $applicationContract;
    }

    public function __invoke(ApplicationRejectedRequest $request, ApplicationId $applicationId): Application
    {
        return $this->applicationContract->update(
            new Application(
                new ApplicationId($applicationId->value()),
                new ApplicationPersonId($request->integer('person_id')),
                new ApplicationVacantId($request->integer('vacant_id')),
                new ApplicationFormId($request->integer('form_id')),
                new ApplicationQuestions($request->input('questions')),
                new ApplicationAnswers($request->input('answers')),
                new ApplicationSalary((float) $request->get('salary')),
                new ApplicationObservation($request->input('observation')),
                new ApplicationStatus(ApplicationModel::REJECTED)
            ),
            $applicationId
        );
    }
}
