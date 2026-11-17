<?php

declare(strict_types=1);

namespace App\Domain\Skill\Values;

final class SkillDescription
{
    private $value;

    public function __construct(string|null $description)
    {
        $this->value = $description;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
