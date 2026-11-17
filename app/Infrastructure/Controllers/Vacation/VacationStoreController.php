<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacation;

use App\Application\VacationUseCase\VacationStoreUseCase;
use App\Http\Requests\Vacation\VacationStoreRequest;
use App\Http\Resources\Vacation\VacationResource;
use App\Infrastructure\Repositories\VacationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function __invoke(VacationStoreRequest $request)
    {
        try {
            $vacationStoreUseCase = new VacationStoreUseCase($this->vacationRepository);
            $vacation = new VacationResource($vacationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $vacation,
                'La solicitud de vacaciones fue registrada exitosamente.',
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
