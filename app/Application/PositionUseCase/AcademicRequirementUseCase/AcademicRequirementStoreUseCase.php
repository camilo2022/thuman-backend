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
use App\Http\Requests\Position\AcademicRequirement\AcademicRequirementStoreRequest;

final class AcademicRequirementStoreUseCase
{
    private $academicRequirementContract;

    public function __construct(AcademicRequirementContract $academicRequirementContract)
    {
        $this->academicRequirementContract = $academicRequirementContract;
    }

    public function __invoke(AcademicRequirementStoreRequest $request): AcademicRequirement
    {
        return $this->academicRequirementContract->store(
            new AcademicRequirement(
                new AcademicRequirementId(null),
                new AcademicRequirementPositionId($request->integer('position_id')),
                new AcademicRequirementAcademicLevelId($request->integer('academic_level_id')),
                new AcademicRequirementAcademicTitle($request->input('academic_title')),
                new AcademicRequirementFormationStatusId($request->integer('formation_status_id'))
            ),
        );
    }
}
