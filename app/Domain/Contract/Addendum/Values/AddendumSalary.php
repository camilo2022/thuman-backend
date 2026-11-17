<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumSalary
{
    private $value;

    public function __construct(float|null $salary)
    {
        if (is_string($salary)) {
            $salary = (float) $salary;
        }

        if (is_null($salary)) {
            $salary = (float) 0;
        }

        $this->value = $salary;
    }

    public function value(): float|null
    {
        return $this->value;
    }
}
