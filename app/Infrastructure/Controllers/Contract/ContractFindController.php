<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractFindUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractFindRequest;
use App\Http\Resources\Contract\ContractResource;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractFindController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractFindRequest $request, int|null $contractId = null)
    {
        try {
            $contractId = new ContractId($contractId);
            $contractFindUseCase = new ContractFindUseCase($this->contractRepository);
            $contract = new ContractResource($contractFindUseCase->__invoke($request, $contractId));
            return $this->successResponse(
                $contract,
                'El contrato fue encontrado exitosamente.',
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
