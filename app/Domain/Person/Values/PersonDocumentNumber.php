<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonDocumentNumber
{
    private $value;
    
    public function __construct(string $documentNumber)
    {
        $this->value = $documentNumber;
    }

    public function value(): string
    {
        return $this->value;
    }
}