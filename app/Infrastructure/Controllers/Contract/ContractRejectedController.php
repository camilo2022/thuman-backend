<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractRejectedUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractRejectedRequest;
use App\Http\Resources\Contract\ContractResource;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractRejectedRequest $request, int $contractId)
    {
        try {
            $contractId = new ContractId($contractId ?? null);
            $contractRejectedUseCase = new ContractRejectedUseCase($this->contractRepository);
            $contract = new ContractResource($contractRejectedUseCase->__invoke($request, $contractId));
            return $this->successResponse(
                $contract,
                'El contrato fue actualizada exitosamente a: RECHAZADO.',
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
