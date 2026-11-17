<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalStartAt
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
