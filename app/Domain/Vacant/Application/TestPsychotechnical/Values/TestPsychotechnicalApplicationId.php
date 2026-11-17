<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Values;

final class TestPsychotechnicalApplicationId
{
    private $value;

    public function __construct(int $applicationId)
    {
        $this->value = $applicationId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
