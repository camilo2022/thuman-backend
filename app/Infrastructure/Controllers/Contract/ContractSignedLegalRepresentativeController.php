<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract;

use App\Application\ContractUseCase\ContractSignedLegalRepresentativeUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractSignedLegalRepresentativeRequest;
use App\Http\Resources\Contract\ContractResource;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractSignedLegalRepresentativeController
{
    use ApiResponser;
    use ApiMessage;
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function __invoke(ContractSignedLegalRepresentativeRequest $request, int $contractId)
    {
        try {
            $contractId = new ContractId($contractId ?? null);
            $contractSignedLegalRepresentativeUseCase = new ContractSignedLegalRepresentativeUseCase($this->contractRepository);
            $contract = new ContractResource($contractSignedLegalRepresentativeUseCase->__invoke($request, $contractId));
            return $this->successResponse(
                $contract,
                'El contrato fue firmado por el representante legal exitosamente.',
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
