<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceSupervisorDialingCountryId
{
    private $value;

    public function __construct(int $supervisorDialingCountryId)
    {
        $this->value = $supervisorDialingCountryId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
