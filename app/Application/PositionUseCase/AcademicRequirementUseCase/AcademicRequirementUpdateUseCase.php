<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\AcademicRequirementUseCase;

use App\Domain\Position\AcademicRequirement\Contracts\AcademicRequirementContract;
use App\Domain\Position\AcademicRequirement\AcademicRequirement;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementAcademicLevelId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementAcademicTitle;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementFormationStatusId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementPositionId;
use App\Http\Requests\Position\AcademicRequirement\AcademicRequirementUpdateRequest;

final class AcademicRequirementUpdateUseCase
{
    private $academicRequirementContract;

    public function __construct(AcademicRequirementContract $academicRequirementContract)
    {
        $this->academicRequirementContract = $academicRequirementContract;
    }

    public function __invoke(AcademicRequirementUpdateRequest $request, AcademicRequirementId $academicRequirementId): AcademicRequirement
    {
        return $this->academicRequirementContract->update(
            new AcademicRequirement(
                new AcademicRequirementId($academicRequirementId->value()),
                new AcademicRequirementPositionId($request->integer('position_id')),
                new AcademicRequirementAcademicLevelId($request->integer('academic_level_id')),
                new AcademicRequirementAcademicTitle($request->input('academic_title')),
                new AcademicRequirementFormationStatusId($request->integer('formation_status_id'))
            ),
            $academicRequirementId
        );
    }
}
