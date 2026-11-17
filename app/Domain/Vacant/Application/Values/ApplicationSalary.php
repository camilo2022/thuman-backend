<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class ApplicationSalary
{
    private $value;

    public function __construct(float|string|null $salary)
    {
        if (is_string($salary)) {
            $salary = (float) $salary;
        }

        if (is_null($salary)) {
            $salary = (float) 0;
        }

        $this->value = $salary;
    }

    public function value(): float
    {
        return $this->value;
    }
}
