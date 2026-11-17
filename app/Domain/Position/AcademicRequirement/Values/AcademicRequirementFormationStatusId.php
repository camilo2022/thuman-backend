<?php

declare(strict_types=1);

namespace App\Domain\Position\AcademicRequirement\Values;

final class AcademicRequirementFormationStatusId
{
    private $value;

    public function __construct(int $formationStatusId)
    {
        $this->value = $formationStatusId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
