<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantSalaryMin
{
    private $value;

    public function __construct(float|string|null $salaryMin)
    {
        if (is_string($salaryMin)) {
            $salaryMin = (float) $salaryMin;
        }

        if (is_null($salaryMin)) {
            $salaryMin = (float) 0;
        }

        $this->value = $salaryMin;
    }

    public function value(): float
    {
        return $this->value;
    }
}
