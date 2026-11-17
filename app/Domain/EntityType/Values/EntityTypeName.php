<?php

declare(strict_types=1);

namespace App\Domain\EntityType\Values;

final class EntityTypeName
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
