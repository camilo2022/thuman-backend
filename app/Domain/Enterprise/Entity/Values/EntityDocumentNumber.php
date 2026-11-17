<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityDocumentNumber
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