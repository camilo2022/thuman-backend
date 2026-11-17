<?php

declare(strict_types=1);

namespace App\Domain\MaritalStatus\Values;

final class MaritalStatusSettings
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
