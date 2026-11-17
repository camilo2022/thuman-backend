<?php

declare(strict_types=1);

namespace App\Domain\RiskManager\Values;

final class RiskManagerItemId
{
    private $value;

    public function __construct(int $itemId)
    {
        $this->value = $itemId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
