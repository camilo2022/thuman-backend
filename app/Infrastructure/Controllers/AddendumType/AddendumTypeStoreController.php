<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AddendumType;

use App\Application\AddendumTypeUseCase\AddendumTypeStoreUseCase;
use App\Http\Requests\AddendumType\AddendumTypeStoreRequest;
use App\Http\Resources\AddendumType\AddendumTypeResource;
use App\Infrastructure\Repositories\AddendumTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumTypeRepository;

    public function __construct(AddendumTypeRepository $addendumTypeRepository)
    {
        $this->addendumTypeRepository = $addendumTypeRepository;
    }

    public function __invoke(AddendumTypeStoreRequest $request)
    {
        try {
            $addendumTypeStoreUseCase = new AddendumTypeStoreUseCase($this->addendumTypeRepository);
            $addendumType = new AddendumTypeResource($addendumTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $addendumType,
                'El tipo de otrosí fue registrado exitosamente.',
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
