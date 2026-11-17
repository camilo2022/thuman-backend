<?php

declare(strict_types=1);

namespace App\Domain\Absence\Values;

final class AbsenceStatus
{
    private $value;

    public function __construct(string|null $status)
    {
        $this->value = $status;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
