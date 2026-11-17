<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonSecondName
{
    private $value;
    
    public function __construct(string|null $secondName)
    {
        $this->value = $secondName;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}