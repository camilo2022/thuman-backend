<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation\Values;

final class CompensationFundAffiliationCompensationFundId
{
    private $value;

    public function __construct(int $compensationFundId)
    {
        $this->value = $compensationFundId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
