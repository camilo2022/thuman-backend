<?php

declare(strict_types=1);

namespace App\Domain\DocumentType\Values;

final class DocumentTypeRelationships
{
    private ?object $person_type;

    public function __construct(
        ?object $person_type = null
    ) {
        $this->person_type = $person_type;
    }

    public function personType(): ?object
    {
        return $this->person_type;
    }

    public function toArray(): array
    {
        return [
            'person_type' => $this->person_type
        ];
    }
}
