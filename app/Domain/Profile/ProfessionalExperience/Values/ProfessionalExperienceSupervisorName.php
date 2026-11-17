<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceSupervisorName
{
    private $value;

    public function __construct(string $supervisorName)
    {
        $this->value = $supervisorName;
    }

    public function value(): string
    {
        return $this->value;
    }
}
