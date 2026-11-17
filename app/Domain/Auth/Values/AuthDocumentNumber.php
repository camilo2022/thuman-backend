<?php

declare(strict_types=1);

namespace App\Domain\Auth\Values;

final class AuthDocumentNumber
{
    private $value;
    
    public function __construct(string|null $documentNumber)
    {
        $this->value = $documentNumber;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}