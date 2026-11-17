<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Values;

final class AllyRelationships
{
    private ?object $entity;
    private ?object $campaign;
    private ?object $country;

    public function __construct(
        ?object $entity = null,
        ?object $campaign = null,
        ?object $country = null
    ) {
        $this->entity = $entity;
        $this->campaign = $campaign;
        $this->country = $country;
    }

    public function entity(): ?object
    {
        return $this->entity;
    }

    public function country(): ?object
    {
        return $this->country;
    }

    public function campaign(): ?object
    {
        return $this->campaign;
    }

    public function toArray(): array
    {
        return [
            'entity' => $this->entity,
            'campaign' => $this->campaign
        ];
    }
}
