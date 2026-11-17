<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceCompanyName
{
    private $value;

    public function __construct(string $companyName)
    {
        $this->value = $companyName;
    }

    public function value(): string
    {
        return $this->value;
    }
}
