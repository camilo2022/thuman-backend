<?php

declare(strict_types=1);

namespace App\Application\PaymentPeriodUseCase;

use App\Domain\PaymentPeriod\PaymentPeriod;
use App\Domain\PaymentPeriod\Contracts\PaymentPeriodContract;
use App\Domain\PaymentPeriod\Values\PaymentPeriodId;

final class PaymentPeriodFindUseCase
{
    private $paymentPeriodContract;

    public function __construct(PaymentPeriodContract $paymentPeriodContract)
    {
        $this->paymentPeriodContract = $paymentPeriodContract;
    }

    public function __invoke(PaymentPeriodId $paymentPeriodId): ?PaymentPeriod
    {
        return $this->paymentPeriodContract->find($paymentPeriodId);
    }
}
