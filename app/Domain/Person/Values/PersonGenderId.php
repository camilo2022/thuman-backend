<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonGenderId
{
    private $value;
    
    public function __construct(int|null $personGenderId)
    {
        $this->value = $personGenderId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}