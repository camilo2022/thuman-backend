<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityType\Values;

final class HealthEntityTypeSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        $this->value = $settings;
    }

    public function value(): object|array
    {
        return $this->value;
    }
}
