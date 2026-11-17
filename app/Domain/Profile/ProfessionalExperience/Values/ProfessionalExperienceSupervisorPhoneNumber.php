<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceSupervisorPhoneNumber
{
    private $value;

    public function __construct(string $phoneNumber)
    {
        $this->value = $phoneNumber;
    }

    public function value(): string
    {
        return $this->value;
    }
}
