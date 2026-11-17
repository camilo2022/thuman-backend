<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Headquarter\Values;

final class HeadquarterRelationships
{
    private ?object $entity;
    private ?object $city;

    public function __construct(
        ?object $entity = null,
        ?object $city = null,
    ) {
        $this->entity = $entity;
        $this->city = $city;
    }

    public function entity(): ?object
    {
        return $this->entity;
    }

    public function city(): ?object
    {
        return $this->city;
    }

    public function toArray(): array
    {
        return [
            'entity' => $this->entity,
            'city' => $this->city
        ];
    }
}
