<?php

declare(strict_types=1);

namespace App\Domain\Employee\Values;

final class EmployeePositionId
{
    private $value;

    public function __construct(int|null $positionId)
    {
        $this->value = $positionId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
