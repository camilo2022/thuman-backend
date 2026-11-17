<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonSecondLastName
{
    private $value;
    
    public function __construct(string|null $secondLastName)
    {
        $this->value = $secondLastName;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}