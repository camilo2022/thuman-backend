<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Values;

final class TestPsychotechnicalEndDate
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
