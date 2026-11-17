<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Values;

final class AllySettings
{
    private $value;

    public function __construct(object|array|null $settings)
    {
        if (is_null($settings)) {
            $settings = [];
        }

        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function works(): array
    {
        return $this->value['works'] ?? [];
    }
}
