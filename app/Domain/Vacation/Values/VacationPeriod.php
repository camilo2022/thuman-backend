<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Values;

final class VacationPeriod
{
    private $value;

    public function __construct(int $period)
    {
        $this->value = $period;
    }

    public function value(): int
    {
        return $this->value;
    }
}
