<?php

declare(strict_types=1);

namespace App\Domain\PersonType\Values;

use Illuminate\Support\Collection;

final class PersonTypeRelationships
{
    private Collection $document_types;

    public function __construct(
        ?Collection $document_types = null,
    ) {
        $this->document_types = $document_types ?? collect();
    }

    public function documentTypes(): Collection
    {
        return $this->document_types;
    }

    public function toArray(): array
    {
        return [
            'document_types' => $this->document_types,
        ];
    }
}
