<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Values;

final class VacationRelationships
{
    private ?object $person;
    private ?object $vacation_type;

    public function __construct(
        ?object $person = null,
        ?object $vacation_type = null,
    ) {
        $this->person = $person;
        $this->vacation_type = $vacation_type;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function vacationType(): ?object
    {
        return $this->vacation_type;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'vacation_type' => $this->vacation_type
        ];
    }
}
