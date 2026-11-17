<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperiencePosition
{
    private $value;

    public function __construct(string $position)
    {
        $this->value = $position;
    }

    public function value(): string
    {
        return $this->value;
    }
}
