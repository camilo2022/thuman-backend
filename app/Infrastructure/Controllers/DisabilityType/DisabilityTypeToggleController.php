<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DisabilityType;

use App\Application\DisabilityTypeUseCase\DisabilityTypeToggleUseCase;
use App\Domain\DisabilityType\Values\DisabilityTypeId;
use App\Http\Requests\DisabilityType\DisabilityTypeToggleRequest;
use App\Http\Resources\DisabilityType\DisabilityTypeResource;
use App\Infrastructure\Repositories\DisabilityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityTypeToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityTypeRepository;

    public function __construct(DisabilityTypeRepository $disabilityTypeRepository)
    {
        $this->disabilityTypeRepository = $disabilityTypeRepository;
    }

    public function __invoke(DisabilityTypeToggleRequest $request, int $disabilityTypeId)
    {
        try {
            $disabilityTypeId = new DisabilityTypeId($disabilityTypeId ?? null);
            $disabilityTypeToggleUseCase = new DisabilityTypeToggleUseCase($this->disabilityTypeRepository);
            $disabilityType = new DisabilityTypeResource($disabilityTypeToggleUseCase->__invoke($request, $disabilityTypeId));
            return $this->successResponse(
                $disabilityType,
                'El tipo de incapacidad fue actualizado exitosamente.',
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
