<?php

declare(strict_types=1);

namespace App\Domain\Position\AcademicRequirement\Values;

final class AcademicRequirementAcademicTitle
{
    private $value;

    public function __construct(string $academicTtitle)
    {
        $this->value = $academicTtitle;
    }

    public function value(): string
    {
        return $this->value;
    }
}
