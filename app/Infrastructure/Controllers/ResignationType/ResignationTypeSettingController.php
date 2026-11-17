<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeSettingUseCase;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Http\Requests\ResignationType\ResignationTypeSettingRequest;
use App\Http\Resources\ResignationType\ResignationTypeResource;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationTypeRepository;

    public function __construct(ResignationTypeRepository $resignationTypeRepository)
    {
        $this->resignationTypeRepository = $resignationTypeRepository;
    }

    public function __invoke(ResignationTypeSettingRequest $request, int $resignationTypeId)
    {
        try {
            $resignationTypeId = new ResignationTypeId($resignationTypeId ?? null);
            $resignationTypeSettingUseCase = new ResignationTypeSettingUseCase($this->resignationTypeRepository);
            $resignationType = new ResignationTypeResource($resignationTypeSettingUseCase->__invoke($request, $resignationTypeId));
            return $this->successResponse(
                $resignationType,
                'La configuración del tipo de renuncia fue realizada exitosamente.',
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
