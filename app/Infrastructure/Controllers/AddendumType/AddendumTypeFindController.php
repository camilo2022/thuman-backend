<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AddendumType;

use App\Application\AddendumTypeUseCase\AddendumTypeFindUseCase;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Http\Resources\AddendumType\AddendumTypeResource;
use App\Infrastructure\Repositories\AddendumTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumTypeFindController
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
            $addendumTypeId = new AddendumTypeId($addendumTypeId ?? null);
            $addendumTypeFindUseCase = new AddendumTypeFindUseCase($this->addendumTypeRepository);
            $addendumType = new AddendumTypeResource($addendumTypeFindUseCase->__invoke($addendumTypeId));
            return $this->successResponse(
                $addendumType,
                'El tipo de otrosí fue encontrada exitosamente.',
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
