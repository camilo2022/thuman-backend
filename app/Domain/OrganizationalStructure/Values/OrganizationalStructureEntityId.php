<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure\Values;

final class OrganizationalStructureEntityId
{
    private $value;

    public function __construct(int|null $entityId)
    {
        $this->value = $entityId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
