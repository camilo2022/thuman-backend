<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperiencePositionDescription
{
    private $value;

    public function __construct(string $positionDescription)
    {
        $this->value = $positionDescription;
    }

    public function value(): string
    {
        return $this->value;
    }
}
