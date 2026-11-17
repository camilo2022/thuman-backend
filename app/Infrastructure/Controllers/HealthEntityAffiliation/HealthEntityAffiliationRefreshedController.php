<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntityAffiliation;

use App\Application\HealthEntityAffiliationUseCase\HealthEntityAffiliationRefreshedUseCase;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationRefreshedRequest;
use App\Http\Resources\HealthEntityAffiliation\HealthEntityAffiliationResource;
use App\Infrastructure\Repositories\HealthEntityAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityAffiliationRefreshedController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityAffiliationRepository;

    public function __construct(HealthEntityAffiliationRepository $healthEntityAffiliationRepository)
    {
        $this->healthEntityAffiliationRepository = $healthEntityAffiliationRepository;
    }

    public function __invoke(HealthEntityAffiliationRefreshedRequest $request, int $healthEntityAffiliationId)
    {
        try {
            $healthEntityAffiliationId = new HealthEntityAffiliationId($healthEntityAffiliationId ?? null);
            $healthEntityAffiliationRefreshedUseCase = new HealthEntityAffiliationRefreshedUseCase($this->healthEntityAffiliationRepository);
            $healthEntityAffiliation = new HealthEntityAffiliationResource($healthEntityAffiliationRefreshedUseCase->__invoke($request, $healthEntityAffiliationId));
            return $this->successResponse(
                $healthEntityAffiliation,
                'La afiliación a la entidad de salud fue actualizada exitosamente a: ACTUALIZAR.',
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
