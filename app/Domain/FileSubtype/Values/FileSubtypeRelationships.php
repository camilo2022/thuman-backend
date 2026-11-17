<?php

declare(strict_types=1);

namespace App\Domain\FileSubtype\Values;

use Illuminate\Support\Collection;

final class FileSubtypeRelationships
{
    private Collection $file_types;

    public function __construct(
        ?Collection $file_types = null,
    ) {
        $this->file_types = $file_types ?? collect();
    }

    public function fileTypes(): Collection
    {
        return $this->file_types;
    }

    public function toArray(): array
    {
        return [
            'file_types' => $this->file_types,
        ];
    }
}
