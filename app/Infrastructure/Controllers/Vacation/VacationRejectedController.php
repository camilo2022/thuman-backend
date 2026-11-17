<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacation;

use App\Application\VacationUseCase\VacationRejectedUseCase;
use App\Domain\Vacation\Values\VacationId;
use App\Http\Requests\Vacation\VacationRejectedRequest;
use App\Http\Resources\Vacation\VacationResource;
use App\Infrastructure\Repositories\VacationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function __invoke(VacationRejectedRequest $request, int $vacationId)
    {
        try {
            $vacationId = new VacationId($vacationId ?? null);
            $vacationRejectedUseCase = new VacationRejectedUseCase($this->vacationRepository);
            $vacation = new VacationResource($vacationRejectedUseCase->__invoke($request, $vacationId));
            return $this->successResponse(
                $vacation,
                'La solicitud de vacaciones fue actualizada exitosamente a: RECHAZADO.',
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
