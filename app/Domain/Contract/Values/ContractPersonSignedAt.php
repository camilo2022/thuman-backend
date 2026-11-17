<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractPersonSignedAt
{
    private $value;

    public function __construct(string|null $personSignedAt)
    {
        $this->value = $personSignedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
