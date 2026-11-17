<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredSkill\Values;

final class RequiredSkillSkillId
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
