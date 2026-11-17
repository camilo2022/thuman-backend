<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntityAffiliation;

use App\Application\HealthEntityAffiliationUseCase\HealthEntityAffiliationStoreUseCase;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationStoreRequest;
use App\Http\Resources\HealthEntityAffiliation\HealthEntityAffiliationResource;
use App\Infrastructure\Repositories\HealthEntityAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityAffiliationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityAffiliationRepository;

    public function __construct(HealthEntityAffiliationRepository $healthEntityAffiliationRepository)
    {
        $this->healthEntityAffiliationRepository = $healthEntityAffiliationRepository;
    }

    public function __invoke(HealthEntityAffiliationStoreRequest $request)
    {
        try {
            $healthEntityAffiliationStoreUseCase = new HealthEntityAffiliationStoreUseCase($this->healthEntityAffiliationRepository);
            $healthEntityAffiliation = new HealthEntityAffiliationResource($healthEntityAffiliationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $healthEntityAffiliation,
                'La afiliación a la entidad de salud fue registrada exitosamente.',
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
