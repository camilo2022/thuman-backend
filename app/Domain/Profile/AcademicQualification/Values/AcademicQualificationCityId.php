<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification\Values;

final class AcademicQualificationCityId
{
    private $value;

    public function __construct(int $cityId)
    {
        $this->value = $cityId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
