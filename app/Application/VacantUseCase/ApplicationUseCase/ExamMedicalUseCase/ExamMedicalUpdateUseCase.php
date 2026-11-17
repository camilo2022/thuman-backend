<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamMedicalUseCase;

use App\Domain\Vacant\Application\ExamMedical\Contracts\ExamMedicalContract;
use App\Domain\Vacant\Application\ExamMedical\ExamMedical;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalAddress;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalApplicationId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalCityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDateTime;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDescription;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalEpidemiologicalProgramId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalHealthEntityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalIndication;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalNeighborhood;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalResult;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalStatus;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalUpdateRequest;
use App\Models\ExamMedical as ExamMedicalModel;

final class ExamMedicalUpdateUseCase
{
    private $examMedicalContract;

    public function __construct(ExamMedicalContract $examMedicalContract)
    {
        $this->examMedicalContract = $examMedicalContract;
    }

    public function __invoke(ExamMedicalUpdateRequest $request, ExamMedicalId $examMedicalId): ExamMedical
    {
        return $this->examMedicalContract->update(
            new ExamMedical(
                new ExamMedicalId($examMedicalId->value()),
                new ExamMedicalApplicationId($request->integer('application_id')),
                new ExamMedicalDateTime($request->input('date_time')),
                new ExamMedicalCityId($request->integer('city_id')),
                new ExamMedicalHealthEntityId($request->integer('health_entity_id')),
                new ExamMedicalAddress($request->input('address')),
                new ExamMedicalNeighborhood($request->input('neighborhood')),
                new ExamMedicalResult(ExamMedicalModel::PENDING),
                new ExamMedicalEpidemiologicalProgramId(null),
                new ExamMedicalDescription(null),
                new ExamMedicalIndication($request->input('indication')),
                new ExamMedicalStatus($request->input('status'))
            ),
            $examMedicalId
        );
    }
}
