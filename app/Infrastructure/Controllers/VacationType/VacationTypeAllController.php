<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\VacationType;

use App\Application\VacationTypeUseCase\VacationTypeAllUseCase;
use App\Http\Requests\VacationType\VacationTypeAllRequest;
use App\Http\Resources\VacationType\VacationTypeCollection;
use App\Infrastructure\Repositories\VacationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacationTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $vacationTypeRepository;

    public function __construct(VacationTypeRepository $vacationTypeRepository)
    {
        $this->vacationTypeRepository = $vacationTypeRepository;
    }

    public function __invoke(VacationTypeAllRequest $request)
    {
        try {
            $vacationTypeAllUseCase = new VacationTypeAllUseCase($this->vacationTypeRepository);
            $vacationTypes = new VacationTypeCollection($vacationTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $vacationTypes,
                'Los tipos de vacaciones consultados exitosamente.',
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
