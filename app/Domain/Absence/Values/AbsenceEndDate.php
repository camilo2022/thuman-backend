<?php

declare(strict_types=1);

namespace App\Domain\Absence\Values;

final class AbsenceEndDate
{
    private $value;

    public function __construct(string $endDate)
    {
        $this->value = $endDate;
    }

    public function value(): string
    {
        return $this->value;
    }
}
