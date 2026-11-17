<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AddendumType;

use App\Application\AddendumTypeUseCase\AddendumTypeSettingUseCase;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Http\Requests\AddendumType\AddendumTypeSettingRequest;
use App\Http\Resources\AddendumType\AddendumTypeResource;
use App\Infrastructure\Repositories\AddendumTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumTypeRepository;

    public function __construct(AddendumTypeRepository $addendumTypeRepository)
    {
        $this->addendumTypeRepository = $addendumTypeRepository;
    }

    public function __invoke(AddendumTypeSettingRequest $request, int $addendumTypeId)
    {
        try {
            $addendumTypeId = new AddendumTypeId($addendumTypeId ?? null);
            $addendumTypeSettingUseCase = new AddendumTypeSettingUseCase($this->addendumTypeRepository);
            $addendumType = new AddendumTypeResource($addendumTypeSettingUseCase->__invoke($request, $addendumTypeId));
            return $this->successResponse(
                $addendumType,
                'La configuración del tipo de otrosí fue realizada exitosamente.',
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
