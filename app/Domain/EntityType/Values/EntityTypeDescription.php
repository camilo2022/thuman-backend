<?php

declare(strict_types=1);

namespace App\Domain\EntityType\Values;

final class EntityTypeDescription
{
    private $value;

    public function __construct(string $description)
    {
        $this->value = $description;
    }

    public function value(): string
    {
        return $this->value;
    }
}
