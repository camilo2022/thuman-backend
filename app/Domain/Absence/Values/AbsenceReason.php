<?php

declare(strict_types=1);

namespace App\Domain\Absence\Values;

final class AbsenceReason
{
    private $value;

    public function __construct(string $reason)
    {
        $this->value = $reason;
    }

    public function value(): string
    {
        return $this->value;
    }
}
