<?php

declare(strict_types=1);

namespace App\Application\PaymentPeriodUseCase;

use App\Domain\PaymentPeriod\Contracts\PaymentPeriodContract;
use App\Http\Requests\PaymentPeriod\PaymentPeriodAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PaymentPeriodAllUseCase
{
    private $paymentPeriodContract;

    public function __construct(PaymentPeriodContract $paymentPeriodContract)
    {
        $this->paymentPeriodContract = $paymentPeriodContract;
    }

    public function __invoke(PaymentPeriodAllRequest $request): LengthAwarePaginator
    {
        return $this->paymentPeriodContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
