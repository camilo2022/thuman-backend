<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalSkill\Values;

final class ProfessionalSkillSkillId
{
    private $value;

    public function __construct(int $skillId)
    {
        $this->value = $skillId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
