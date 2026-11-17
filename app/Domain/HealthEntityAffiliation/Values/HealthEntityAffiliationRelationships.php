<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityAffiliation\Values;

final class HealthEntityAffiliationRelationships
{
    private ?object $person;
    private ?object $country;
    private ?object $health_entity;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $country = null,
        ?object $health_entity = null,
        ?object $file = null
    ) {
        $this->person = $person;
        $this->country = $country;
        $this->health_entity = $health_entity;
        $this->file = $file;
    }

    public function person(): ?array
    {
        return $this->person;
    }

    public function country(): ?array
    {
        return $this->country;
    }

    public function healthEntity(): ?array
    {
        return $this->health_entity;
    }

    public function file(): ?array
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'country' => $this->country,
            'health_entity' => $this->health_entity,
            'file' => $this->file
        ];
    }
}
