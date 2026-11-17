<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractStoreUseCase;
use App\Http\Requests\Contract\ContractStoreRequest;
use App\Http\Resources\Contract\ContractResource;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractStoreRequest $request)
    {
        try {
            $contractStoreUseCase = new ContractStoreUseCase($this->contractRepository);
            $contract = new ContractResource($contractStoreUseCase->__invoke($request));
            return $this->successResponse(
                $contract,
                'El contrato registrado exitosamente.',
                201
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
