<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceCityId
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
