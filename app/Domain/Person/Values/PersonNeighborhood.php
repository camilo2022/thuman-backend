<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonNeighborhood
{
    private $value;
    
    public function __construct(string|null $neighborhood)
    {
        $this->value = $neighborhood;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}