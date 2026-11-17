<?php

declare(strict_types=1);

namespace App\Domain\Employee\Values;

final class EmployeeRelationships
{
    private ?object $person;
    private ?object $headquarter;
    private ?object $position;
    private ?object $campaign;

    public function __construct(
        ?object $person = null,
        ?object $headquarter = null,
        ?object $position = null,
        ?object $campaign = null
    ) {
        $this->person = $person;
        $this->headquarter = $headquarter;
        $this->position = $position;
        $this->campaign = $campaign;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function headquarter(): ?object
    {
        return $this->headquarter;
    }

    public function position(): ?object
    {
        return $this->position;
    }

    public function campaign(): ?object
    {
        return $this->campaign;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'headquarter' => $this->headquarter,
            'position' => $this->position,
            'campaign' => $this->campaign
        ];
    }
}
