<?php

declare(strict_types=1);

namespace App\Domain\DocumentType\Values;

final class DocumentTypePersonTypeId
{
    private $value;

    public function __construct(int|null $personTypeId)
    {
        $this->value = $personTypeId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
