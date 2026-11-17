<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure\Values;

final class OrganizationalStructureRelationships
{
    private ?object $entity = null;
    private ?object $position = null;
    private ?object $parentPosition = null;

    public function __construct(
        ?object $entity = null,
        ?object $position = null,
        ?object $parentPosition = null
    ) {
        $this->entity = $entity;
        $this->position = $position;
        $this->parentPosition = $parentPosition;
    }

    public function entity(): ?object
    {
        return $this->entity;
    }

    public function position(): ?object
    {
        return $this->position;
    }

    public function parentPosition(): ?object
    {
        return $this->parentPosition;
    }

    public function toArray(): array
    {
        return [
            'entity' => $this->entity,
            'position' => $this->position,
            'parent_position' => $this->parentPosition,
        ];
    }
}
