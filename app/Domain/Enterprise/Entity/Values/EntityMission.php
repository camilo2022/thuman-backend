<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityMission
{
    private $value;
    
    public function __construct(string|null $mission)
    {
        $this->value = $mission;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}