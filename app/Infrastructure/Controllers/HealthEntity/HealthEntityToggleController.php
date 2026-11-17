<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntity;

use App\Application\HealthEntityUseCase\HealthEntityToggleUseCase;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Http\Requests\HealthEntity\HealthEntityToggleRequest;
use App\Http\Resources\HealthEntity\HealthEntityResource;
use App\Infrastructure\Repositories\HealthEntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityRepository;

    public function __construct(HealthEntityRepository $healthEntityRepository)
    {
        $this->healthEntityRepository = $healthEntityRepository;
    }

    public function __invoke(HealthEntityToggleRequest $request, int $healthEntityId)
    {
        try {
            $healthEntityId = new HealthEntityId($healthEntityId ?? null);
            $healthEntityToggleUseCase = new HealthEntityToggleUseCase($this->healthEntityRepository);
            $healthEntity = new HealthEntityResource($healthEntityToggleUseCase->__invoke($request, $healthEntityId));
            return $this->successResponse(
                $healthEntity,
                'El país fue asignado/removido a la entidad de salud exitosamente.',
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
