<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonDocumentTypeId
{
    private $value;
    
    public function __construct(int $personDocumentTypeId)
    {
        $this->value = $personDocumentTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}