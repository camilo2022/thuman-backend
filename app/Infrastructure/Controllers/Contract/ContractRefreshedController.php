<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractRefreshedUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractRefreshedRequest;
use App\Http\Resources\Contract\ContractResource;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractRefreshedController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractRefreshedRequest $request, int $contractId)
    {
        try {
            $contractId = new ContractId($contractId ?? null);
            $contractRefreshedUseCase = new ContractRefreshedUseCase($this->contractRepository);
            $contract = new ContractResource($contractRefreshedUseCase->__invoke($request, $contractId));
            return $this->successResponse(
                $contract,
                'El contrato fue actualizado exitosamente a: ACTUALIZAR.',
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
