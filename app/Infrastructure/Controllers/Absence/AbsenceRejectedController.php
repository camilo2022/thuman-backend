<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Absence;

use App\Application\AbsenceUseCase\AbsenceRejectedUseCase;
use App\Domain\Absence\Values\AbsenceId;
use App\Http\Requests\Absence\AbsenceRejectedRequest;
use App\Http\Resources\Absence\AbsenceResource;
use App\Infrastructure\Repositories\AbsenceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceRepository;

    public function __construct(AbsenceRepository $absenceRepository)
    {
        $this->absenceRepository = $absenceRepository;
    }

    public function __invoke(AbsenceRejectedRequest $request, int $absenceId)
    {
        try {
            $absenceId = new AbsenceId($absenceId ?? null);
            $absenceRejectedUseCase = new AbsenceRejectedUseCase($this->absenceRepository);
            $absence = new AbsenceResource($absenceRejectedUseCase->__invoke($request, $absenceId));
            return $this->successResponse(
                $absence,
                'La solicitud de permiso fue actualizada exitosamente a: RECHAZADO.',
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
