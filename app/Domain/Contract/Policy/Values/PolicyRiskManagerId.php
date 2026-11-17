<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyRiskManagerId
{
    private $value;

    public function __construct(int $riskManagerId)
    {
        $this->value = $riskManagerId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
