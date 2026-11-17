<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Values;

final class VacationStartDate
{
    private $value;

    public function __construct(string|null $startDate)
    {
        $this->value = $startDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
