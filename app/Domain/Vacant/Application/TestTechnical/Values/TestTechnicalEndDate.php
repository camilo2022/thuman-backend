<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalEndDate
{
    private $value;

    public function __construct(string $endDate)
    {
        $this->value = $endDate;
    }

    public function value(): string
    {
        return $this->value;
    }
}
