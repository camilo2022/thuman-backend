<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalEndAt
{
    private $value;

    public function __construct(string|null $endAt)
    {
        $this->value = $endAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
