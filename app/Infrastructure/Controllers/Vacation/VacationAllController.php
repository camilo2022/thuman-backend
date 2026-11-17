<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacation;

use App\Application\VacationUseCase\VacationAllUseCase;
use App\Http\Requests\Vacation\VacationAllRequest;
use App\Http\Resources\Vacation\VacationCollection;
use App\Infrastructure\Repositories\VacationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationAllController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function __invoke(VacationAllRequest $request)
    {
        try {
            $vacationAllUseCase = new VacationAllUseCase($this->vacationRepository);
            $vacations = new VacationCollection($vacationAllUseCase->__invoke($request));
            return $this->successResponse(
                $vacations,
                'Las solicitudes de vacaciones fueron consultadas exitosamente.',
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
