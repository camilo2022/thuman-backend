<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractTransportAllowance
{
    private $value;

    public function __construct(float|null $transportAllowance)
    {
        if (is_string($transportAllowance)) {
            $transportAllowance = (float) $transportAllowance;
        }

        if (is_null($transportAllowance)) {
            $transportAllowance = (float) 0;
        }

        $this->value = $transportAllowance;
    }

    public function value(): float|null
    {
        return $this->value;
    }
}
