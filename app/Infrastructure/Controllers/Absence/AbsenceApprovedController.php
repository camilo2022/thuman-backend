<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Absence;

use App\Application\AbsenceUseCase\AbsenceApprovedUseCase;
use App\Domain\Absence\Values\AbsenceId;
use App\Http\Requests\Absence\AbsenceApprovedRequest;
use App\Http\Resources\Absence\AbsenceResource;
use App\Infrastructure\Repositories\AbsenceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceRepository;

    public function __construct(AbsenceRepository $absenceRepository)
    {
        $this->absenceRepository = $absenceRepository;
    }

    public function __invoke(AbsenceApprovedRequest $request, int $absenceId)
    {
        try {
            $absenceId = new AbsenceId($absenceId ?? null);
            $absenceApprovedUseCase = new AbsenceApprovedUseCase($this->absenceRepository);
            $absence = new AbsenceResource($absenceApprovedUseCase->__invoke($request, $absenceId));
            return $this->successResponse(
                $absence,
                'La solicitud de permiso fue actualizada exitosamente a: APROBADO.',
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
