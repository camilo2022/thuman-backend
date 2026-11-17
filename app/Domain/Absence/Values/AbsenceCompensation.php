<?php

declare(strict_types=1);

namespace App\Domain\Absence\Values;

final class AbsenceCompensation
{
    private $value;

    public function __construct(string|null $compensation)
    {
        $this->value = $compensation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
