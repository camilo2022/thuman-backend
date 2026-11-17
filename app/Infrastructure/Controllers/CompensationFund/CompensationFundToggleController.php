<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFund;

use App\Application\CompensationFundUseCase\CompensationFundToggleUseCase;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Http\Requests\CompensationFund\CompensationFundToggleRequest;
use App\Http\Resources\CompensationFund\CompensationFundResource;
use App\Infrastructure\Repositories\CompensationFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundRepository;

    public function __construct(CompensationFundRepository $compensationFundRepository)
    {
        $this->compensationFundRepository = $compensationFundRepository;
    }

    public function __invoke(CompensationFundToggleRequest $request, int $compensationFundId)
    {
        try {
            $compensationFundId = new CompensationFundId($compensationFundId ?? null);
            $compensationFundToggleUseCase = new CompensationFundToggleUseCase($this->compensationFundRepository);
            $compensationFund = new CompensationFundResource($compensationFundToggleUseCase->__invoke($request, $compensationFundId));
            return $this->successResponse(
                $compensationFund,
                'El país fue asignado/removido a la caja de compensación exitosamente.',
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
