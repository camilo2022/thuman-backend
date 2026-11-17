<?php

declare(strict_types=1);

namespace App\Domain\AbsenceType\Values;

final class AbsenceTypeId
{
    private $value;

    public function __construct(int|null $id)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
