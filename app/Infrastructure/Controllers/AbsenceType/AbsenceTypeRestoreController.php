<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AbsenceType;

use App\Application\AbsenceTypeUseCase\AbsenceTypeRestoreUseCase;
use App\Domain\AbsenceType\Values\AbsenceTypeId;
use App\Http\Resources\AbsenceType\AbsenceTypeResource;
use App\Infrastructure\Repositories\AbsenceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceTypeRepository;

    public function __construct(AbsenceTypeRepository $absenceTypeRepository)
    {
        $this->absenceTypeRepository = $absenceTypeRepository;
    }

    public function __invoke(int $absenceTypeId)
    {
        try {
            $absenceTypeId = new AbsenceTypeId($absenceTypeId);
            $absenceTypeRestoreUseCase = new AbsenceTypeRestoreUseCase($this->absenceTypeRepository);
            $absenceType = new AbsenceTypeResource($absenceTypeRestoreUseCase->__invoke($absenceTypeId));
            return $this->successResponse(
                $absenceType,
                'El tipo de permiso fue activado exitosamente.',
                204
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
