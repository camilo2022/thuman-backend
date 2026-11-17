<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification\Values;

final class AcademicQualificationAcademicInstitution
{
    private $value;

    public function __construct(string $academicInstitution)
    {
        $this->value = $academicInstitution;
    }

    public function value(): string
    {
        return $this->value;
    }
}
