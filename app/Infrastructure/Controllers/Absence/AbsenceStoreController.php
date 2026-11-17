<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Absence;

use App\Application\AbsenceUseCase\AbsenceStoreUseCase;
use App\Http\Requests\Absence\AbsenceStoreRequest;
use App\Http\Resources\Absence\AbsenceResource;
use App\Infrastructure\Repositories\AbsenceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceRepository;

    public function __construct(AbsenceRepository $absenceRepository)
    {
        $this->absenceRepository = $absenceRepository;
    }

    public function __invoke(AbsenceStoreRequest $request)
    {
        try {
            $absenceStoreUseCase = new AbsenceStoreUseCase($this->absenceRepository);
            $absence = new AbsenceResource($absenceStoreUseCase->__invoke($request));
            return $this->successResponse(
                $absence,
                'La solicitud de permiso fue registrada exitosamente.',
                201
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
