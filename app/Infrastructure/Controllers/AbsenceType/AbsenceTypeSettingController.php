<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AbsenceType;

use App\Application\AbsenceTypeUseCase\AbsenceTypeSettingUseCase;
use App\Domain\AbsenceType\Values\AbsenceTypeId;
use App\Http\Requests\AbsenceType\AbsenceTypeSettingRequest;
use App\Http\Resources\AbsenceType\AbsenceTypeResource;
use App\Infrastructure\Repositories\AbsenceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceTypeRepository;

    public function __construct(AbsenceTypeRepository $absenceTypeRepository)
    {
        $this->absenceTypeRepository = $absenceTypeRepository;
    }

    public function __invoke(AbsenceTypeSettingRequest $request, int $absenceTypeId)
    {
        try {
            $absenceTypeId = new AbsenceTypeId($absenceTypeId ?? null);
            $absenceTypeSettingUseCase = new AbsenceTypeSettingUseCase($this->absenceTypeRepository);
            $absenceType = new AbsenceTypeResource($absenceTypeSettingUseCase->__invoke($request, $absenceTypeId));
            return $this->successResponse(
                $absenceType,
                'La configuración del tipo de permiso fue realizada exitosamente.',
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
