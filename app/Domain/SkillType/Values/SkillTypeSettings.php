<?php

declare(strict_types=1);

namespace App\Domain\SkillType\Values;

final class SkillTypeSettings
{
    private array $value;

    public function __construct(object|array $settings)
    {
        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function regex(): string
    {
        return $this->value['regex'];
    }

    public function message(): string
    {
        return $this->value['message'];
    }
}