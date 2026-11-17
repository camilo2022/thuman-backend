<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacation;

use App\Application\VacationUseCase\VacationUpdateUseCase;
use App\Domain\Vacation\Values\VacationId;
use App\Http\Requests\Vacation\VacationUpdateRequest;
use App\Http\Resources\Vacation\VacationResource;
use App\Infrastructure\Repositories\VacationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function __invoke(VacationUpdateRequest $request, int $vacationId)
    {
        try {
            $vacationId = new VacationId($vacationId ?? null);
            $vacationUpdateUseCase = new VacationUpdateUseCase($this->vacationRepository);
            $vacation = new VacationResource($vacationUpdateUseCase->__invoke($request, $vacationId));
            return $this->successResponse(
                $vacation,
                'La solicitud de vacaciones fue actualizada exitosamente.',
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
