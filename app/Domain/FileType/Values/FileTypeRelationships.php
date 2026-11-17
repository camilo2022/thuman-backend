<?php

declare(strict_types=1);

namespace App\Domain\FileType\Values;

use Illuminate\Support\Collection;

final class FileTypeRelationships
{
    private Collection $file_subtypes;

    public function __construct(
        ?Collection $file_subtypes = null,
    ) {
        $this->file_subtypes = $file_subtypes ?? collect();
    }

    public function fileSubtypes(): Collection
    {
        return $this->file_subtypes;
    }

    public function toArray(): array
    {
        return [
            'file_subtypes' => $this->file_subtypes,
        ];
    }
}
