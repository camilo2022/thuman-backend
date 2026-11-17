<?php

declare(strict_types=1);

namespace App\Domain\ServiceType\Values;

final class ServiceTypeName
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
