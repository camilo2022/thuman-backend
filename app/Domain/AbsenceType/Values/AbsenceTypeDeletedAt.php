<?php

declare(strict_types=1);

namespace App\Domain\AbsenceType\Values;

final class AbsenceTypeDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
