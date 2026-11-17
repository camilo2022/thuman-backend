<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\RiskManagerAffiliation;

use App\Application\RiskManagerAffiliationUseCase\RiskManagerAffiliationStoreUseCase;
use App\Http\Requests\RiskManagerAffiliation\RiskManagerAffiliationStoreRequest;
use App\Http\Resources\RiskManagerAffiliation\RiskManagerAffiliationResource;
use App\Infrastructure\Repositories\RiskManagerAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RiskManagerAffiliationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $riskManagerAffiliationRepository;

    public function __construct(RiskManagerAffiliationRepository $riskManagerAffiliationRepository)
    {
        $this->riskManagerAffiliationRepository = $riskManagerAffiliationRepository;
    }

    public function __invoke(RiskManagerAffiliationStoreRequest $request)
    {
        try {
            $riskManagerAffiliationStoreUseCase = new RiskManagerAffiliationStoreUseCase($this->riskManagerAffiliationRepository);
            $riskManagerAffiliation = new RiskManagerAffiliationResource($riskManagerAffiliationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $riskManagerAffiliation,
                'La afiliación a la administradora de riesgo laboral fue registrada exitosamente.',
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
