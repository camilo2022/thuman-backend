<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeUpdateUseCase;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Http\Requests\ResignationType\ResignationTypeUpdateRequest;
use App\Http\Resources\ResignationType\ResignationTypeResource;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationTypeRepository;

    public function __construct(ResignationTypeRepository $resignationTypeRepository)
    {
        $this->resignationTypeRepository = $resignationTypeRepository;
    }

    public function __invoke(ResignationTypeUpdateRequest $request, int $resignationTypeId)
    {
        try {
            $resignationTypeId = new ResignationTypeId($resignationTypeId ?? null);
            $resignationTypeUpdateUseCase = new ResignationTypeUpdateUseCase($this->resignationTypeRepository);
            $resignationType = new ResignationTypeResource($resignationTypeUpdateUseCase->__invoke($request, $resignationTypeId));
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
