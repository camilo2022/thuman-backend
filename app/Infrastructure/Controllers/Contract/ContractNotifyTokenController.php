<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractNotifyTokenUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractNotifyTokenRequest;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractNotifyTokenController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractNotifyTokenRequest $request, int|null $contractId = null)
    {
        try {
            $contractId = new ContractId($contractId);
            $contractNotifyTokenUseCase = new ContractNotifyTokenUseCase($this->contractRepository);
            $contractNotifyTokenUseCase->__invoke($request, $contractId);
            return $this->successResponse(
                '',
                'Se ha enviado exitosamente el código de acceso para firmar el contrato al correo electrónico asociado a la persona.',
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
