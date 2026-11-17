<?php

declare(strict_types=1);

namespace App\Domain\ContractType\Values;

use Illuminate\Support\Collection;

final class ContractTypeRelationships
{
    private Collection $file_subtypes_id;

    public function __construct(
        ?Collection $file_subtypes_id = null
    ) {
        $this->file_subtypes_id = $file_subtypes_id ?? collect();
    }

    public function fileSubtypesId(): Collection
    {
        return $this->file_subtypes_id;
    }

    public function toArray(): array
    {
        return [
            'file_subtypes_id' => $this->file_subtypes_id
        ];
    }
}
