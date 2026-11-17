<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\VacationType;

use App\Application\VacationTypeUseCase\VacationTypeSettingUseCase;
use App\Domain\VacationType\Values\VacationTypeId;
use App\Http\Requests\VacationType\VacationTypeSettingRequest;
use App\Http\Resources\VacationType\VacationTypeResource;
use App\Infrastructure\Repositories\VacationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationTypeRepository;

    public function __construct(VacationTypeRepository $vacationTypeRepository)
    {
        $this->vacationTypeRepository = $vacationTypeRepository;
    }

    public function __invoke(VacationTypeSettingRequest $request, int $vacationTypeId)
    {
        try {
            $vacationTypeId = new VacationTypeId($vacationTypeId ?? null);
            $vacationTypeSettingUseCase = new VacationTypeSettingUseCase($this->vacationTypeRepository);
            $vacationType = new VacationTypeResource($vacationTypeSettingUseCase->__invoke($request, $vacationTypeId));
            return $this->successResponse(
                $vacationType,
                'La configuración del tipo de vacaciones fue realizada exitosamente.',
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
