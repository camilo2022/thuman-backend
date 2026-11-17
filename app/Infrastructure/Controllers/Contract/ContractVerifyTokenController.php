<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractVerifyTokenUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractVerifyTokenRequest;
use App\Http\Resources\Contract\ContractResource;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractVerifyTokenController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractVerifyTokenRequest $request, int $contractId)
    {
        try {
            $contractId = new ContractId($contractId ?? null);
            $contractVerifyTokenUseCase = new ContractVerifyTokenUseCase($this->contractRepository);
            $contract = new ContractResource($contractVerifyTokenUseCase->__invoke($request, $contractId));
            return $this->successResponse(
                $contract,
                'Se ha verificado exitosamente código de acceso para firma de contrato.',
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
