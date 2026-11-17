<?php

declare(strict_types=1);

namespace App\Domain\Disability\Values;

final class DisabilityStartDate
{
    private $value;

    public function __construct(string $startDate)
    {
        $this->value = $startDate;
    }

    public function value(): string
    {
        return $this->value;
    }
}
