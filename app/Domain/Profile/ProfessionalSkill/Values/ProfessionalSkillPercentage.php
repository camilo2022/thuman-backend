<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalSkill\Values;

final class ProfessionalSkillPercentage
{
    private $value;

    public function __construct(int $percentage)
    {
        $this->value = $percentage;
    }

    public function value(): int
    {
        return $this->value;
    }
}
