<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\VacationType;

use App\Application\VacationTypeUseCase\VacationTypeRestoreUseCase;
use App\Domain\VacationType\Values\VacationTypeId;
use App\Http\Resources\VacationType\VacationTypeResource;
use App\Infrastructure\Repositories\VacationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationTypeRepository;

    public function __construct(VacationTypeRepository $vacationTypeRepository)
    {
        $this->vacationTypeRepository = $vacationTypeRepository;
    }

    public function __invoke(int $vacationTypeId)
    {
        try {
            $vacationTypeId = new VacationTypeId($vacationTypeId);
            $vacationTypeRestoreUseCase = new VacationTypeRestoreUseCase($this->vacationTypeRepository);
            $vacationType = new VacationTypeResource($vacationTypeRestoreUseCase->__invoke($vacationTypeId));
            return $this->successResponse(
                $vacationType,
                'El tipo de vacaciones fue activado exitosamente.',
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
