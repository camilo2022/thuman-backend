<?php

declare(strict_types=1);

namespace App\Domain\Employee\Values;

final class EmployeeHeadquarterId
{
    private $value;

    public function __construct(int|null $headquarterId)
    {
        $this->value = $headquarterId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
