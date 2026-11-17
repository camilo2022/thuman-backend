<?php

declare(strict_types=1);

namespace App\Domain\WorkDay\Values;

final class WorkDayDescription
{
    private $value;

    public function __construct(string $description)
    {
        $this->value = $description;
    }

    public function value(): string
    {
        return $this->value;
    }
}
