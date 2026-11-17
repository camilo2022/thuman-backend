<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntityAffiliation;

use App\Application\HealthEntityAffiliationUseCase\HealthEntityAffiliationUpdateUseCase;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationUpdateRequest;
use App\Http\Resources\HealthEntityAffiliation\HealthEntityAffiliationResource;
use App\Infrastructure\Repositories\HealthEntityAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityAffiliationUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityAffiliationRepository;

    public function __construct(HealthEntityAffiliationRepository $healthEntityAffiliationRepository)
    {
        $this->healthEntityAffiliationRepository = $healthEntityAffiliationRepository;
    }

    public function __invoke(HealthEntityAffiliationUpdateRequest $request, int $healthEntityAffiliationId)
    {
        try {
            $healthEntityAffiliationId = new HealthEntityAffiliationId($healthEntityAffiliationId ?? null);
            $healthEntityAffiliationUpdateUseCase = new HealthEntityAffiliationUpdateUseCase($this->healthEntityAffiliationRepository);
            $healthEntityAffiliation = new HealthEntityAffiliationResource($healthEntityAffiliationUpdateUseCase->__invoke($request, $healthEntityAffiliationId));
            return $this->successResponse(
                $healthEntityAffiliation,
                'La afiliación a la entidad de salud fue actualizada exitosamente.',
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
