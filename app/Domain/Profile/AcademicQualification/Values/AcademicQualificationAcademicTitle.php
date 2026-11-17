<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification\Values;

final class AcademicQualificationAcademicTitle
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
