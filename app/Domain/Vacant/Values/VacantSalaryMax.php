<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantSalaryMax
{
    private $value;

    public function __construct(float|null $salaryMax)
    {
        if (is_string($salaryMax)) {
            $salaryMax = (float) $salaryMax;
        }

        if (is_null($salaryMax)) {
            $salaryMax = (float) 0;
        }

        $this->value = $salaryMax;
    }

    public function value(): float|null
    {
        return $this->value;
    }
}
