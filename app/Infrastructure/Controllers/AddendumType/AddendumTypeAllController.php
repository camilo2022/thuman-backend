<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AddendumType;

use App\Application\AddendumTypeUseCase\AddendumTypeAllUseCase;
use App\Http\Requests\AddendumType\AddendumTypeAllRequest;
use App\Http\Resources\AddendumType\AddendumTypeCollection;
use App\Infrastructure\Repositories\AddendumTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumTypeRepository;

    public function __construct(AddendumTypeRepository $addendumTypeRepository)
    {
        $this->addendumTypeRepository = $addendumTypeRepository;
    }

    public function __invoke(AddendumTypeAllRequest $request)
    {
        try {
            $addendumTypeAllUseCase = new AddendumTypeAllUseCase($this->addendumTypeRepository);
            $addendumTypes = new AddendumTypeCollection($addendumTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $addendumTypes,
                'Los tipos de otrosí consultados exitosamente.',
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
