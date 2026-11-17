<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractPersonStatus
{
    private $value;

    public function __construct(string|null $personStatus)
    {
        $this->value = $personStatus;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
