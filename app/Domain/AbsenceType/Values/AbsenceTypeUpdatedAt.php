<?php

declare(strict_types=1);

namespace App\Domain\AbsenceType\Values;

final class AbsenceTypeUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
