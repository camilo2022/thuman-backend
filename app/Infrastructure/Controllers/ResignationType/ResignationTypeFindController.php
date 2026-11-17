<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeFindUseCase;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Http\Resources\ResignationType\ResignationTypeResource;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationTypeRepository;

    public function __construct(ResignationTypeRepository $resignationTypeRepository)
    {
        $this->resignationTypeRepository = $resignationTypeRepository;
    }

    public function __invoke(int $resignationTypeId)
    {
        try {
            $resignationTypeId = new ResignationTypeId($resignationTypeId ?? null);
            $resignationTypeFindUseCase = new ResignationTypeFindUseCase($this->resignationTypeRepository);
            $resignationType = new ResignationTypeResource($resignationTypeFindUseCase->__invoke($resignationTypeId));
            return $this->successResponse(
                $resignationType,
                'El tipo de renuncia fue encontrada exitosamente.',
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
