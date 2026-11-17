<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyCode
{
    private $value;

    public function __construct(string $code)
    {
        $this->value = $code;
    }

    public function value(): string
    {
        return $this->value;
    }
}
