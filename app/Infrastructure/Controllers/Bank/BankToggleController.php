<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Bank;

use App\Application\BankUseCase\BankToggleUseCase;
use App\Domain\Bank\Values\BankId;
use App\Http\Requests\Bank\BankToggleRequest;
use App\Http\Resources\Bank\BankResource;
use App\Infrastructure\Repositories\BankRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function __invoke(BankToggleRequest $request, int $bankId)
    {
        try {
            $bankId = new BankId($bankId ?? null);
            $bankToggleUseCase = new BankToggleUseCase($this->bankRepository);
            $bank = new BankResource($bankToggleUseCase->__invoke($request, $bankId));
            return $this->successResponse(
                $bank,
                'El país fue asignado/removido al banco exitosamente.',
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
