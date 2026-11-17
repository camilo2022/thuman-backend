<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AbsenceType;

use App\Application\AbsenceTypeUseCase\AbsenceTypeAllUseCase;
use App\Http\Requests\AbsenceType\AbsenceTypeAllRequest;
use App\Http\Resources\AbsenceType\AbsenceTypeCollection;
use App\Infrastructure\Repositories\AbsenceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AbsenceTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $absenceTypeRepository;

    public function __construct(AbsenceTypeRepository $absenceTypeRepository)
    {
        $this->absenceTypeRepository = $absenceTypeRepository;
    }

    public function __invoke(AbsenceTypeAllRequest $request)
    {
        try {
            $absenceTypeAllUseCase = new AbsenceTypeAllUseCase($this->absenceTypeRepository);
            $absenceTypes = new AbsenceTypeCollection($absenceTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $absenceTypes,
                'Los tipos de permisos consultados exitosamente.',
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
