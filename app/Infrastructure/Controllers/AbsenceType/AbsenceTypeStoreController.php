<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AbsenceType;

use App\Application\AbsenceTypeUseCase\AbsenceTypeStoreUseCase;
use App\Http\Requests\AbsenceType\AbsenceTypeStoreRequest;
use App\Http\Resources\AbsenceType\AbsenceTypeResource;
use App\Infrastructure\Repositories\AbsenceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceTypeRepository;

    public function __construct(AbsenceTypeRepository $absenceTypeRepository)
    {
        $this->absenceTypeRepository = $absenceTypeRepository;
    }

    public function __invoke(AbsenceTypeStoreRequest $request)
    {
        try {
            $absenceTypeStoreUseCase = new AbsenceTypeStoreUseCase($this->absenceTypeRepository);
            $absenceType = new AbsenceTypeResource($absenceTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $absenceType,
                'El tipo de permiso fue registrado exitosamente.',
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
