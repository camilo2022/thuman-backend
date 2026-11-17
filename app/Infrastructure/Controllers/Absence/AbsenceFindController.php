<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Absence;

use App\Application\AbsenceUseCase\AbsenceFindUseCase;
use App\Domain\Absence\Values\AbsenceId;
use App\Http\Resources\Absence\AbsenceResource;
use App\Infrastructure\Repositories\AbsenceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceFindController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceRepository;

    public function __construct(AbsenceRepository $absenceRepository)
    {
        $this->absenceRepository = $absenceRepository;
    }

    public function __invoke(int $absenceId)
    {
        try {
            $absenceId = new AbsenceId($absenceId ?? null);
            $absenceFindUseCase = new AbsenceFindUseCase($this->absenceRepository);
            $absence = new AbsenceResource($absenceFindUseCase->__invoke($absenceId));
            return $this->successResponse(
                $absence,
                'La solicitud de permiso fue encontrada exitosamente.',
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
