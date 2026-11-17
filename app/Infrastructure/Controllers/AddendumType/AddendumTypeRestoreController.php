<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AddendumType;

use App\Application\AddendumTypeUseCase\AddendumTypeRestoreUseCase;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Http\Resources\AddendumType\AddendumTypeResource;
use App\Infrastructure\Repositories\AddendumTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumTypeRepository;

    public function __construct(AddendumTypeRepository $addendumTypeRepository)
    {
        $this->addendumTypeRepository = $addendumTypeRepository;
    }

    public function __invoke(int $addendumTypeId)
    {
        try {
            $addendumTypeId = new AddendumTypeId($addendumTypeId);
            $addendumTypeRestoreUseCase = new AddendumTypeRestoreUseCase($this->addendumTypeRepository);
            $addendumType = new AddendumTypeResource($addendumTypeRestoreUseCase->__invoke($addendumTypeId));
            return $this->successResponse(
                $addendumType,
                'El tipo de otrosí fue activado exitosamente.',
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
