<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacation;

use App\Application\VacationUseCase\VacationFindUseCase;
use App\Domain\Vacation\Values\VacationId;
use App\Http\Resources\Vacation\VacationResource;
use App\Infrastructure\Repositories\VacationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationFindController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function __invoke(int $vacationId)
    {
        try {
            $vacationId = new VacationId($vacationId ?? null);
            $vacationFindUseCase = new VacationFindUseCase($this->vacationRepository);
            $vacation = new VacationResource($vacationFindUseCase->__invoke($vacationId));
            return $this->successResponse(
                $vacation,
                'La solicitud de vacaciones fue encontrada exitosamente.',
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
