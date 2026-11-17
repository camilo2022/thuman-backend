<?php

declare(strict_types=1);

namespace App\Domain\Position\AcademicRequirement\Values;

final class AcademicRequirementAcademicLevelId
{
    private $value;

    public function __construct(int $academicLevelId)
    {
        $this->value = $academicLevelId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
