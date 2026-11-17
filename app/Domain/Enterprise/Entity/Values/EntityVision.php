<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityVision
{
    private $value;
    
    public function __construct(string|null $vision)
    {
        $this->value = $vision;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}