<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractPaymentPeriodId
{
    private $value;

    public function __construct(int $paymentPeriodId)
    {
        $this->value = $paymentPeriodId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
