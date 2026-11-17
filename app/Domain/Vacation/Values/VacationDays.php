<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Values;

final class VacationDays
{
    private $value;

    public function __construct(int|null $days)
    {
        $this->value = $days;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
