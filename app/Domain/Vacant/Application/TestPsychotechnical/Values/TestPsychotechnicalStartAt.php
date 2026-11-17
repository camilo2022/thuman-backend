<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Values;

final class TestPsychotechnicalStartAt
{
    private $value;

    public function __construct(string|null $startAt)
    {
        $this->value = $startAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
