<?php

declare(strict_types=1);

namespace App\Domain\DisabilityType\Values;

final class DisabilityTypeName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
