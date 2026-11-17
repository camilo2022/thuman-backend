<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntityAffiliation;

use App\Application\HealthEntityAffiliationUseCase\HealthEntityAffiliationFindUseCase;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationFindRequest;
use App\Http\Resources\HealthEntityAffiliation\HealthEntityAffiliationResource;
use App\Infrastructure\Repositories\HealthEntityAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityAffiliationFindController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityAffiliationRepository;

    public function __construct(HealthEntityAffiliationRepository $healthEntityAffiliationRepository)
    {
        $this->healthEntityAffiliationRepository = $healthEntityAffiliationRepository;
    }

    public function __invoke(HealthEntityAffiliationFindRequest $request, int|null $healthEntityAffiliationId = null)
    {
        try {
            $healthEntityAffiliationId = new HealthEntityAffiliationId($healthEntityAffiliationId);
            $healthEntityAffiliationFindUseCase = new HealthEntityAffiliationFindUseCase($this->healthEntityAffiliationRepository);
            $healthEntityAffiliation = new HealthEntityAffiliationResource($healthEntityAffiliationFindUseCase->__invoke($request, $healthEntityAffiliationId));
            return $this->successResponse(
                $healthEntityAffiliation,
                'La afiliación a la entidad de salud fue encontrada exitosamente.',
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
