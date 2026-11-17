<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyInsuredValue
{
    private $value;

    public function __construct(float|null $insuredValue)
    {
        if (is_string($insuredValue)) {
            $insuredValue = (float) $insuredValue;
        }

        if (is_null($insuredValue)) {
            $insuredValue = (float) 0;
        }

        $this->value = $insuredValue;
    }

    public function value(): float|null
    {
        return $this->value;
    }
}
