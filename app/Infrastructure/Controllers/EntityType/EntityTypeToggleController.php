<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\EntityType;

use App\Application\EntityTypeUseCase\EntityTypeToggleUseCase;
use App\Domain\EntityType\Values\EntityTypeId;
use App\Http\Requests\EntityType\EntityTypeToggleRequest;
use App\Http\Resources\EntityType\EntityTypeResource;
use App\Infrastructure\Repositories\EntityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EntityTypeToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $entityTypeRepository;

    public function __construct(EntityTypeRepository $entityTypeRepository)
    {
        $this->entityTypeRepository = $entityTypeRepository;
    }

    public function __invoke(EntityTypeToggleRequest $request, int $entityTypeId)
    {
        try {
            $entityTypeId = new EntityTypeId($entityTypeId ?? null);
            $entityTypeToggleUseCase = new EntityTypeToggleUseCase($this->entityTypeRepository);
            $entityType = new EntityTypeResource($entityTypeToggleUseCase->__invoke($request, $entityTypeId));
            return $this->successResponse(
                $entityType,
                'El país fue asignado/removido al tipo de entidad exitosamente.',
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
