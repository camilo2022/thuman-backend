<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PaymentPeriod;

use App\Application\PaymentPeriodUseCase\PaymentPeriodAllUseCase;
use App\Http\Requests\PaymentPeriod\PaymentPeriodAllRequest;
use App\Http\Resources\PaymentPeriod\PaymentPeriodCollection;
use App\Infrastructure\Repositories\PaymentPeriodRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PaymentPeriodAllController
{
    use ApiResponser;
    use ApiMessage;
    private $paymentPeriodRepository;

    public function __construct(PaymentPeriodRepository $paymentPeriodRepository)
    {
        $this->paymentPeriodRepository = $paymentPeriodRepository;
    }

    public function __invoke(PaymentPeriodAllRequest $request)
    {
        try {
            $paymentPeriodAllUseCase = new PaymentPeriodAllUseCase($this->paymentPeriodRepository);
            $paymentPeriods = new PaymentPeriodCollection($paymentPeriodAllUseCase->__invoke($request));
            return $this->successResponse(
                $paymentPeriods,
                'Los períodos de pago consultados exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
