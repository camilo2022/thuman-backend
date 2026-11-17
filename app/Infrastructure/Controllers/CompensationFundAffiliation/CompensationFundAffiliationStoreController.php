<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFundAffiliation;

use App\Application\CompensationFundAffiliationUseCase\CompensationFundAffiliationStoreUseCase;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationStoreRequest;
use App\Http\Resources\CompensationFundAffiliation\CompensationFundAffiliationResource;
use App\Infrastructure\Repositories\CompensationFundAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundAffiliationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundAffiliationRepository;

    public function __construct(CompensationFundAffiliationRepository $compensationFundAffiliationRepository)
    {
        $this->compensationFundAffiliationRepository = $compensationFundAffiliationRepository;
    }

    public function __invoke(CompensationFundAffiliationStoreRequest $request)
    {
        try {
            $compensationFundAffiliationStoreUseCase = new CompensationFundAffiliationStoreUseCase($this->compensationFundAffiliationRepository);
            $compensationFundAffiliation = new CompensationFundAffiliationResource($compensationFundAffiliationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $compensationFundAffiliation,
                'La afiliación a la caja de compensación fue registrada exitosamente.',
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
