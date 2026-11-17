<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DisabilityType;

use App\Application\DisabilityTypeUseCase\DisabilityTypeRestoreUseCase;
use App\Domain\DisabilityType\Values\DisabilityTypeId;
use App\Http\Resources\DisabilityType\DisabilityTypeResource;
use App\Infrastructure\Repositories\DisabilityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityTypeRepository;

    public function __construct(DisabilityTypeRepository $disabilityTypeRepository)
    {
        $this->disabilityTypeRepository = $disabilityTypeRepository;
    }

    public function __invoke(int $disabilityTypeId)
    {
        try {
            $disabilityTypeId = new DisabilityTypeId($disabilityTypeId);
            $disabilityTypeRestoreUseCase = new DisabilityTypeRestoreUseCase($this->disabilityTypeRepository);
            $disabilityType = new DisabilityTypeResource($disabilityTypeRestoreUseCase->__invoke($disabilityTypeId));
            return $this->successResponse(
                $disabilityType,
                'El tipo de incapacidad fue activada exitosamente.',
                204
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
