<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure\Values;

final class OrganizationalStructureParentPositionId
{
    private $value;

    public function __construct(int|null $parentPositionId)
    {
        $this->value = $parentPositionId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
