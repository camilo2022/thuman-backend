<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification\Values;

final class AcademicQualificationProfileId
{
    private $value;

    public function __construct(int $profileId)
    {
        $this->value = $profileId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
