<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalStartDate
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
