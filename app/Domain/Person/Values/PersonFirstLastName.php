<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonFirstLastName
{
    private $value;
    
    public function __construct(string $firstLastName)
    {
        $this->value = $firstLastName;
    }

    public function value(): string
    {
        return $this->value;
    }
}