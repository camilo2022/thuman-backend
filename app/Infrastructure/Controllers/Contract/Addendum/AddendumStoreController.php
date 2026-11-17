<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Addendum;

use App\Application\ContractUseCase\AddendumUseCase\AddendumStoreUseCase;
use App\Http\Requests\Contract\Addendum\AddendumStoreRequest;
use App\Http\Resources\Contract\Addendum\AddendumResource;
use App\Infrastructure\Repositories\AddendumRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumRepository;

    public function __construct(AddendumRepository $addendumRepository)
    {
        $this->addendumRepository = $addendumRepository;
    }

    public function __invoke(AddendumStoreRequest $request)
    {
        try {
            $addendumStoreUseCase = new AddendumStoreUseCase($this->addendumRepository);
            $addendum = new AddendumResource($addendumStoreUseCase->__invoke($request));
            return $this->successResponse(
                $addendum,
                'El otro sí registrado exitosamente.',
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
