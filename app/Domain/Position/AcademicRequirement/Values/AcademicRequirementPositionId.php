<?php

declare(strict_types=1);

namespace App\Domain\Position\AcademicRequirement\Values;

final class AcademicRequirementPositionId
{
    private $value;

    public function __construct(int $positionId)
    {
        $this->value = $positionId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
