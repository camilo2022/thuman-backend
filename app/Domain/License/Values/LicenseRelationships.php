<?php

declare(strict_types=1);

namespace App\Domain\License\Values;

final class LicenseRelationships
{
    private ?object $person;
    private ?object $license_type;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $license_type = null,
        ?object $file = null,
    ) {
        $this->person = $person;
        $this->license_type = $license_type;
        $this->file = $file;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function licenseType(): ?object
    {
        return $this->license_type;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'license_type' => $this->license_type,
            'file' => $this->file
        ];
    }
}
