<?php

declare(strict_types=1);

namespace App\Domain\Sex\Values;

final class SexSettings
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
