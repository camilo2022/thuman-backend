<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonAddress
{
    private $value;
    
    public function __construct(string|null $address)
    {
        $this->value = $address;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}