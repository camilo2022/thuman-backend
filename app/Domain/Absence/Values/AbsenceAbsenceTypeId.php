<?php

declare(strict_types=1);

namespace App\Domain\Absence\Values;

final class AbsenceAbsenceTypeId
{
    private $value;

    public function __construct(int $absenceTypeId)
    {
        $this->value = $absenceTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
