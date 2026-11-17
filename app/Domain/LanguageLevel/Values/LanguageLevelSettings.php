<?php

declare(strict_types=1);

namespace App\Domain\LanguageLevel\Values;

final class LanguageLevelSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): object|array
    {
        return $this->value;
    }

    public function level(): string
    {
        return $this->value['level'];
    }
}
