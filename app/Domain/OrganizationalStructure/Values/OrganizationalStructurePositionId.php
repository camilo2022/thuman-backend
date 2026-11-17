<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure\Values;

final class OrganizationalStructurePositionId
{
    private $value;

    public function __construct(int|null $positionId)
    {
        $this->value = $positionId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
