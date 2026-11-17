<?php

declare(strict_types=1);

namespace App\Domain\PaymentPeriod\Contracts;

use App\Domain\PaymentPeriod\PaymentPeriod;
use App\Domain\PaymentPeriod\Values\PaymentPeriodId;
use Illuminate\Pagination\LengthAwarePaginator;

interface PaymentPeriodContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(PaymentPeriodId $paymentPeriodId): ?PaymentPeriod;
}
