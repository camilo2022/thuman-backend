<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredSkill\Values;

final class RequiredSkillPercentage
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
