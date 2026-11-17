<?php

declare(strict_types=1);

namespace App\Domain\RiskManager\Values;

final class RiskManagerDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
