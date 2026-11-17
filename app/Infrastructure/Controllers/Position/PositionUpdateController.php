<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position;

use App\Application\PositionUseCase\PositionUpdateUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\PositionUpdateRequest;
use App\Http\Resources\Position\PositionResource;
use App\Infrastructure\Repositories\PositionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PositionUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function __invoke(PositionUpdateRequest $request, int $positionId)
    {
        try {
            $positionId = new PositionId($positionId ?? null);
            $positionUpdateUseCase = new PositionUpdateUseCase($this->positionRepository);
            $position = new PositionResource($positionUpdateUseCase->__invoke($request, $positionId));
            return $this->successResponse(
                $position,
                'El perfil de cargo fue actualizado exitosamente.',
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
