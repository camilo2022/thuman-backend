<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredSkill\Values;

final class RequiredSkillUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
