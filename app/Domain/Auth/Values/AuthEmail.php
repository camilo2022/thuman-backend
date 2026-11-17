<?php

declare(strict_types=1);

namespace App\Domain\Auth\Values;

final class AuthEmail
{
    private $value;
    
    public function __construct(string|null $email)
    {
        $this->value = $email;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}