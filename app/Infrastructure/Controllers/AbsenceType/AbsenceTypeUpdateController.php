<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AbsenceType;

use App\Application\AbsenceTypeUseCase\AbsenceTypeUpdateUseCase;
use App\Domain\AbsenceType\Values\AbsenceTypeId;
use App\Http\Requests\AbsenceType\AbsenceTypeUpdateRequest;
use App\Http\Resources\AbsenceType\AbsenceTypeResource;
use App\Infrastructure\Repositories\AbsenceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceTypeRepository;

    public function __construct(AbsenceTypeRepository $absenceTypeRepository)
    {
        $this->absenceTypeRepository = $absenceTypeRepository;
    }

    public function __invoke(AbsenceTypeUpdateRequest $request, int $absenceTypeId)
    {
        try {
            $absenceTypeId = new AbsenceTypeId($absenceTypeId ?? null);
            $absenceTypeUpdateUseCase = new AbsenceTypeUpdateUseCase($this->absenceTypeRepository);
            $absenceType = new AbsenceTypeResource($absenceTypeUpdateUseCase->__invoke($request, $absenceTypeId));
            return $this->successResponse(
                $absenceType,
                'El tipo de permiso fue actualizado exitosamente.',
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
