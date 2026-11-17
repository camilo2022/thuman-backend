<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantWorkDayId
{
    private $value;

    public function __construct(int $workDayId)
    {
        $this->value = $workDayId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
