<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumPaymentPeriodId
{
    private $value;

    public function __construct(int|null $paymentPeriodId)
    {
        $this->value = $paymentPeriodId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
