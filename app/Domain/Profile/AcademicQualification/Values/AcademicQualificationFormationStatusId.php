<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification\Values;

final class AcademicQualificationFormationStatusId
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
