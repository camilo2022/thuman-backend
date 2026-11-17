<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityEntityTypeId
{
    private $value;
    
    public function __construct(int|null $entityTypeId)
    {
        $this->value = $entityTypeId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}