<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure\Values;

final class OrganizationalStructureEndDate
{
    private $value;

    public function __construct(string|null $endDate)
    {
        $this->value = $endDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
