<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceCompanyAddress
{
    private $value;

    public function __construct(string $companyAddress)
    {
        $this->value = $companyAddress;
    }

    public function value(): string
    {
        return $this->value;
    }
}
