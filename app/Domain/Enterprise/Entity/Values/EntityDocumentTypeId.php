<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityDocumentTypeId
{
    private $value;
    
    public function __construct(int|null $documentTypeId)
    {
        $this->value = $documentTypeId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}