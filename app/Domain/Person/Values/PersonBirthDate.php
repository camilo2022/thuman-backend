<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonBirthDate
{
    private $value;
    
    public function __construct(string|null $birthDate)
    {
        $this->value = $birthDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}