<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacation;

use App\Application\VacationUseCase\VacationAvailabilityUseCase;
use App\Http\Requests\Vacation\VacationAvailabilityRequest;
use App\Infrastructure\Repositories\VacationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationAvailabilityController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function __invoke(VacationAvailabilityRequest $request)
    {
        try {
            $vacationAvailabilityUseCase = new VacationAvailabilityUseCase($this->vacationRepository);
            $vacations = $vacationAvailabilityUseCase->__invoke($request);
            return $this->successResponse(
                $vacations,
                'La disponibilidad de períodos y días de vacaciones fue consultada exitosamente.',
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
