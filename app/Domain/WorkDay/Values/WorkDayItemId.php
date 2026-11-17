<?php

declare(strict_types=1);

namespace App\Domain\WorkDay\Values;

final class WorkDayItemId
{
    private $value;

    public function __construct(int $itemId)
    {
        $this->value = $itemId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
