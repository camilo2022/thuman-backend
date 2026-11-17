<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFundAffiliation;

use App\Application\CompensationFundAffiliationUseCase\CompensationFundAffiliationApprovedUseCase;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationApprovedRequest;
use App\Http\Resources\CompensationFundAffiliation\CompensationFundAffiliationResource;
use App\Infrastructure\Repositories\CompensationFundAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundAffiliationApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundAffiliationRepository;

    public function __construct(CompensationFundAffiliationRepository $compensationFundAffiliationRepository)
    {
        $this->compensationFundAffiliationRepository = $compensationFundAffiliationRepository;
    }

    public function __invoke(CompensationFundAffiliationApprovedRequest $request, int $compensationFundAffiliationId)
    {
        try {
            $compensationFundAffiliationId = new CompensationFundAffiliationId($compensationFundAffiliationId ?? null);
            $compensationFundAffiliationApprovedUseCase = new CompensationFundAffiliationApprovedUseCase($this->compensationFundAffiliationRepository);
            $compensationFundAffiliation = new CompensationFundAffiliationResource($compensationFundAffiliationApprovedUseCase->__invoke($request, $compensationFundAffiliationId));
            return $this->successResponse(
                $compensationFundAffiliation,
                'La afiliación a la caja de compensación fue actualizada exitosamente a: APROBADO.',
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
