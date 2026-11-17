<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonFirstName
{
    private $value;
    
    public function __construct(string $firstName)
    {
        $this->value = $firstName;
    }

    public function value(): string
    {
        return $this->value;
    }
}