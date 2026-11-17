<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position;

use App\Application\PositionUseCase\PositionStoreUseCase;
use App\Http\Requests\Position\PositionStoreRequest;
use App\Http\Resources\Position\PositionResource;
use App\Infrastructure\Repositories\PositionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PositionStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function __invoke(PositionStoreRequest $request)
    {
        try {
            $positionStoreUseCase = new PositionStoreUseCase($this->positionRepository);
            $position = new PositionResource($positionStoreUseCase->__invoke($request));
            return $this->successResponse(
                $position,
                'El perfil de cargo fue registrado exitosamente.',
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
