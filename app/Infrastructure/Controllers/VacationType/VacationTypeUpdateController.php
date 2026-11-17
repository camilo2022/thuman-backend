<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\VacationType;

use App\Application\VacationTypeUseCase\VacationTypeUpdateUseCase;
use App\Domain\VacationType\Values\VacationTypeId;
use App\Http\Requests\VacationType\VacationTypeUpdateRequest;
use App\Http\Resources\VacationType\VacationTypeResource;
use App\Infrastructure\Repositories\VacationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationTypeRepository;

    public function __construct(VacationTypeRepository $vacationTypeRepository)
    {
        $this->vacationTypeRepository = $vacationTypeRepository;
    }

    public function __invoke(VacationTypeUpdateRequest $request, int $vacationTypeId)
    {
        try {
            $vacationTypeId = new VacationTypeId($vacationTypeId ?? null);
            $vacationTypeUpdateUseCase = new VacationTypeUpdateUseCase($this->vacationTypeRepository);
            $vacationType = new VacationTypeResource($vacationTypeUpdateUseCase->__invoke($request, $vacationTypeId));
            return $this->successResponse(
                $vacationType,
                'El tipo de vacaciones fue actualizado exitosamente.',
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
