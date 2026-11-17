<?php

declare(strict_types=1);

namespace App\Domain\Skill\Values;

final class SkillSkillTypeId
{
    private $value;

    public function __construct(int|null $typeSkillId)
    {
        $this->value = $typeSkillId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
