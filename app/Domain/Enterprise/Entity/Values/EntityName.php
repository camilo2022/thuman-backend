<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityName
{
    private $value;
    
    public function __construct(string|null $name)
    {
        $this->value = $name;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}