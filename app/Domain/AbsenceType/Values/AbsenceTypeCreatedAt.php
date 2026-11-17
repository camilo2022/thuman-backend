<?php

declare(strict_types=1);

namespace App\Domain\AbsenceType\Values;

final class AbsenceTypeCreatedAt
{
    private $value;

    public function __construct(string|null $createdAt)
    {
        $this->value = $createdAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
