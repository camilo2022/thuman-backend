<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractAllUseCase;
use App\Http\Requests\Contract\ContractAllRequest;
use App\Http\Resources\Contract\ContractCollection;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractAllController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractAllRequest $request)
    {
        try {
            $contractAllUseCase = new ContractAllUseCase($this->contractRepository);
            $contracts = new ContractCollection($contractAllUseCase->__invoke($request));
            return $this->successResponse(
                $contracts,
                'Los contratos consultados exitosamente.',
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
