<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal\Values;

final class AuthInternalEmployeeId
{
    private $value;

    public function __construct(int|null $employeeId)
    {
        $this->value = $employeeId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
