<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DisabilityType;

use App\Application\DisabilityTypeUseCase\DisabilityTypeSettingUseCase;
use App\Domain\DisabilityType\Values\DisabilityTypeId;
use App\Http\Requests\DisabilityType\DisabilityTypeSettingRequest;
use App\Http\Resources\DisabilityType\DisabilityTypeResource;
use App\Infrastructure\Repositories\DisabilityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityTypeRepository;

    public function __construct(DisabilityTypeRepository $disabilityTypeRepository)
    {
        $this->disabilityTypeRepository = $disabilityTypeRepository;
    }

    public function __invoke(DisabilityTypeSettingRequest $request, int $disabilityTypeId)
    {
        try {
            $disabilityTypeId = new DisabilityTypeId($disabilityTypeId ?? null);
            $disabilityTypeSettingUseCase = new DisabilityTypeSettingUseCase($this->disabilityTypeRepository);
            $disabilityType = new DisabilityTypeResource($disabilityTypeSettingUseCase->__invoke($request, $disabilityTypeId));
            return $this->successResponse(
                $disabilityType,
                'La configuración del tipo de incapacidad fue realizada exitosamente.',
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
