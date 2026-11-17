<?php

declare(strict_types=1);

namespace App\Domain\Profile\Values;

final class ProfileRelationships
{
    private ?object $file;

    public function __construct(
        ?object $file = null
    ) {
        $this->file = $file;
    }

    public function file(): ?array
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'file' => $this->file
        ];
    }
}
