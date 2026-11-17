<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyValue
{
    private $value;

    public function __construct(float|null $value)
    {
        if (is_string($value)) {
            $value = (float) $value;
        }

        if (is_null($value)) {
            $value = (float) 0;
        }

        $this->value = $value;
    }

    public function value(): float|null
    {
        return $this->value;
    }
}
