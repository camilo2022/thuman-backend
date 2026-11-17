<?php

declare(strict_types=1);

namespace App\Domain\Auth\Values;

final class AuthPassword
{
    private $value;
    
    public function __construct(string $password)
    {
        $this->value = $password;
    }

    public function value(): string
    {
        return $this->value;
    }
}