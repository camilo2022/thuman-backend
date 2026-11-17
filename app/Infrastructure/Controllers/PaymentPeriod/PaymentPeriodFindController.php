<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PaymentPeriod;

use App\Application\PaymentPeriodUseCase\PaymentPeriodFindUseCase;
use App\Domain\PaymentPeriod\Values\PaymentPeriodId;
use App\Http\Resources\PaymentPeriod\PaymentPeriodResource;
use App\Infrastructure\Repositories\PaymentPeriodRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PaymentPeriodFindController
{
    use ApiResponser;
    use ApiMessage;
    private $paymentPeriodRepository;

    public function __construct(PaymentPeriodRepository $paymentPeriodRepository)
    {
        $this->paymentPeriodRepository = $paymentPeriodRepository;
    }

    public function __invoke(int $paymentPeriodId)
    {
        try {
            $paymentPeriodId = new PaymentPeriodId($paymentPeriodId ?? null);
            $paymentPeriodFindUseCase = new PaymentPeriodFindUseCase($this->paymentPeriodRepository);
            $paymentPeriod = new PaymentPeriodResource($paymentPeriodFindUseCase->__invoke($paymentPeriodId));
            return $this->successResponse(
                $paymentPeriod,
                'El período de pago fue encontrada exitosamente.',
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
