<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractPreviewUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractPreviewController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(int $contractId)
    {
        try {
            $contractId = new ContractId($contractId ?? null);
            $contractPreviewUseCase = new ContractPreviewUseCase($this->contractRepository);
            $contract = $contractPreviewUseCase->__invoke($contractId);
            return $this->successResponse(
                $contract,
                'La plantilla fue configurada exitosamente.',
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
