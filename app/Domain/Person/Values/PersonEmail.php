<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonEmail
{
    private $value;
    
    public function __construct(string $email)
    {
        $this->value = $email;
    }

    public function value(): string
    {
        return $this->value;
    }
}