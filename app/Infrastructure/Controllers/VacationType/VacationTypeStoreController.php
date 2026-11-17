<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\VacationType;

use App\Application\VacationTypeUseCase\VacationTypeStoreUseCase;
use App\Http\Requests\VacationType\VacationTypeStoreRequest;
use App\Http\Resources\VacationType\VacationTypeResource;
use App\Infrastructure\Repositories\VacationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationTypeRepository;

    public function __construct(VacationTypeRepository $vacationTypeRepository)
    {
        $this->vacationTypeRepository = $vacationTypeRepository;
    }

    public function __invoke(VacationTypeStoreRequest $request)
    {
        try {
            $vacationTypeStoreUseCase = new VacationTypeStoreUseCase($this->vacationTypeRepository);
            $vacationType = new VacationTypeResource($vacationTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $vacationType,
                'El tipo de vacaciones fue registrado exitosamente.',
                201
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
