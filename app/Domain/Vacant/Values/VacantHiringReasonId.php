<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantHiringReasonId
{
    private $value;

    public function __construct(int $hiringReasonId)
    {
        $this->value = $hiringReasonId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
