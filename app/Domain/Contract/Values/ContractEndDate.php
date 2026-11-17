<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractEndDate
{
    private $value;

    public function __construct(string|null $endDate)
    {
        $this->value = $endDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
