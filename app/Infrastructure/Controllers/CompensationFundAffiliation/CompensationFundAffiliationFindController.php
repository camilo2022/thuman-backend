<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFundAffiliation;

use App\Application\CompensationFundAffiliationUseCase\CompensationFundAffiliationFindUseCase;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationFindRequest;
use App\Http\Resources\CompensationFundAffiliation\CompensationFundAffiliationResource;
use App\Infrastructure\Repositories\CompensationFundAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundAffiliationFindController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundAffiliationRepository;

    public function __construct(CompensationFundAffiliationRepository $compensationFundAffiliationRepository)
    {
        $this->compensationFundAffiliationRepository = $compensationFundAffiliationRepository;
    }

    public function __invoke(CompensationFundAffiliationFindRequest $request, int|null $compensationFundAffiliationId = null)
    {
        try {
            $compensationFundAffiliationId = new CompensationFundAffiliationId($compensationFundAffiliationId);
            $compensationFundAffiliationFindUseCase = new CompensationFundAffiliationFindUseCase($this->compensationFundAffiliationRepository);
            $compensationFundAffiliation = new CompensationFundAffiliationResource($compensationFundAffiliationFindUseCase->__invoke($request, $compensationFundAffiliationId));
            return $this->successResponse(
                $compensationFundAffiliation,
                'La afiliación a la caja de compensación fue encontrada exitosamente.',
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
