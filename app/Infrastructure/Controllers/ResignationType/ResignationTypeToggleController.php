<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeToggleUseCase;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Http\Requests\ResignationType\ResignationTypeToggleRequest;
use App\Http\Resources\ResignationType\ResignationTypeResource;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationTypeToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationTypeRepository;

    public function __construct(ResignationTypeRepository $resignationTypeRepository)
    {
        $this->resignationTypeRepository = $resignationTypeRepository;
    }

    public function __invoke(ResignationTypeToggleRequest $request, int $resignationTypeId)
    {
        try {
            $resignationTypeId = new ResignationTypeId($resignationTypeId ?? null);
            $resignationTypeToggleUseCase = new ResignationTypeToggleUseCase($this->resignationTypeRepository);
            $resignationType = new ResignationTypeResource($resignationTypeToggleUseCase->__invoke($request, $resignationTypeId));
            return $this->successResponse(
                $resignationType,
                'El tipo de renuncia fue actualizado exitosamente.',
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
