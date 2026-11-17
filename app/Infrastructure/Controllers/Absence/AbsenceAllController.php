<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Absence;

use App\Application\AbsenceUseCase\AbsenceAllUseCase;
use App\Http\Requests\Absence\AbsenceAllRequest;
use App\Http\Resources\Absence\AbsenceCollection;
use App\Infrastructure\Repositories\AbsenceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceAllController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceRepository;

    public function __construct(AbsenceRepository $absenceRepository)
    {
        $this->absenceRepository = $absenceRepository;
    }

    public function __invoke(AbsenceAllRequest $request)
    {
        try {
            $absenceAllUseCase = new AbsenceAllUseCase($this->absenceRepository);
            $absences = new AbsenceCollection($absenceAllUseCase->__invoke($request));
            return $this->successResponse(
                $absences,
                'Las solicitudes de permisos consultadas exitosamente.',
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
