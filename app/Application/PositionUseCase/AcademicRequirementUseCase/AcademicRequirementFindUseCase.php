<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\AcademicRequirementUseCase;

use App\Domain\Position\AcademicRequirement\AcademicRequirement;
use App\Domain\Position\AcademicRequirement\Contracts\AcademicRequirementContract;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;

final class AcademicRequirementFindUseCase
{
    private $academicRequirementContract;

    public function __construct(AcademicRequirementContract $academicRequirementContract)
    {
        $this->academicRequirementContract = $academicRequirementContract;
    }

    public function __invoke(AcademicRequirementId $academicRequirementId): ?AcademicRequirement
    {
        return $this->academicRequirementContract->find($academicRequirementId);
    }
}
