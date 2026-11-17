<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractHeadquarterId
{
    private $value;

    public function __construct(int $headquarterId)
    {
        $this->value = $headquarterId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
