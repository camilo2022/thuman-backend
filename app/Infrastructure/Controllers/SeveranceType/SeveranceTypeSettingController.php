<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SeveranceType;

use App\Application\SeveranceTypeUseCase\SeveranceTypeSettingUseCase;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Http\Requests\SeveranceType\SeveranceTypeSettingRequest;
use App\Http\Resources\SeveranceType\SeveranceTypeResource;
use App\Infrastructure\Repositories\SeveranceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceTypeRepository;

    public function __construct(SeveranceTypeRepository $severanceTypeRepository)
    {
        $this->severanceTypeRepository = $severanceTypeRepository;
    }

    public function __invoke(SeveranceTypeSettingRequest $request, int $severanceTypeId)
    {
        try {
            $severanceTypeId = new SeveranceTypeId($severanceTypeId ?? null);
            $severanceTypeSettingUseCase = new SeveranceTypeSettingUseCase($this->severanceTypeRepository);
            $severanceType = new SeveranceTypeResource($severanceTypeSettingUseCase->__invoke($request, $severanceTypeId));
            return $this->successResponse(
                $severanceType,
                'La configuración del tipo de cesantías fue realizada exitosamente.',
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
