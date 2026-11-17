<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeStoreUseCase;
use App\Http\Requests\ResignationType\ResignationTypeStoreRequest;
use App\Http\Resources\ResignationType\ResignationTypeResource;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationTypeRepository;

    public function __construct(ResignationTypeRepository $resignationTypeRepository)
    {
        $this->resignationTypeRepository = $resignationTypeRepository;
    }

    public function __invoke(ResignationTypeStoreRequest $request)
    {
        try {
            $resignationTypeStoreUseCase = new ResignationTypeStoreUseCase($this->resignationTypeRepository);
            $resignationType = new ResignationTypeResource($resignationTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $resignationType,
                'El tipo de renuncia fue registrado exitosamente.',
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
