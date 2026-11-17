<?php

declare(strict_types=1);

namespace App\Domain\RiskManager\Values;

final class RiskManagerUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
