<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure\Values;

final class OrganizationalStructureStartDate
{
    private $value;

    public function __construct(string|null $startDate)
    {
        $this->value = $startDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
