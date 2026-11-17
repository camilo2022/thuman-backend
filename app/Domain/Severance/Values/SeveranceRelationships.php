<?php

declare(strict_types=1);

namespace App\Domain\Severance\Values;

final class SeveranceRelationships
{
    private ?object $person;
    private ?object $severance_type;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $severance_type = null,
        ?object $file = null,
    ) {
        $this->person = $person;
        $this->severance_type = $severance_type;
        $this->file = $file;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function severanceType(): ?object
    {
        return $this->severance_type;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'severance_type' => $this->severance_type,
            'file' => $this->file
        ];
    }
}
