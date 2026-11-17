<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeRestoreUseCase;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Http\Resources\ResignationType\ResignationTypeResource;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationTypeRestoreController
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
            $resignationTypeId = new ResignationTypeId($resignationTypeId);
            $resignationTypeRestoreUseCase = new ResignationTypeRestoreUseCase($this->resignationTypeRepository);
            $resignationType = new ResignationTypeResource($resignationTypeRestoreUseCase->__invoke($resignationTypeId));
            return $this->successResponse(
                $resignationType,
                'El tipo de renuncia fue activada exitosamente.',
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
