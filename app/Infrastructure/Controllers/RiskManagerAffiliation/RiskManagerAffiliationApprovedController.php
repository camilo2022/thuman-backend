<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\RiskManagerAffiliation;

use App\Application\RiskManagerAffiliationUseCase\RiskManagerAffiliationApprovedUseCase;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationId;
use App\Http\Requests\RiskManagerAffiliation\RiskManagerAffiliationApprovedRequest;
use App\Http\Resources\RiskManagerAffiliation\RiskManagerAffiliationResource;
use App\Infrastructure\Repositories\RiskManagerAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RiskManagerAffiliationApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $riskManagerAffiliationRepository;

    public function __construct(RiskManagerAffiliationRepository $riskManagerAffiliationRepository)
    {
        $this->riskManagerAffiliationRepository = $riskManagerAffiliationRepository;
    }

    public function __invoke(RiskManagerAffiliationApprovedRequest $request, int $riskManagerAffiliationId)
    {
        try {
            $riskManagerAffiliationId = new RiskManagerAffiliationId($riskManagerAffiliationId ?? null);
            $riskManagerAffiliationApprovedUseCase = new RiskManagerAffiliationApprovedUseCase($this->riskManagerAffiliationRepository);
            $riskManagerAffiliation = new RiskManagerAffiliationResource($riskManagerAffiliationApprovedUseCase->__invoke($request, $riskManagerAffiliationId));
            return $this->successResponse(
                $riskManagerAffiliation,
                'La afiliación a la administradora de riesgo laboral fue actualizada exitosamente a: APROBADO.',
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
