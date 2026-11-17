<?php

declare(strict_types=1);

namespace App\Domain\Absence\Values;

final class AbsenceRelationships
{
    private ?object $person;
    private ?object $absence_type;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $absence_type = null,
        ?object $file = null,
    ) {
        $this->person = $person;
        $this->absence_type = $absence_type;
        $this->file = $file;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function absenceType(): ?object
    {
        return $this->absence_type;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'absence_type' => $this->absence_type,
            'file' => $this->file
        ];
    }
}
