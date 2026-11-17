<?php

declare(strict_types=1);

namespace App\Domain\RiskManager\Values;

final class RiskManagerCreatedAt
{
    private $value;

    public function __construct(string|null $createdAt)
    {
        $this->value = $createdAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
