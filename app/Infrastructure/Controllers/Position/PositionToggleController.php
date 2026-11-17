<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position;

use App\Application\PositionUseCase\PositionToggleUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\PositionToggleRequest;
use App\Http\Resources\Position\PositionResource;
use App\Infrastructure\Repositories\PositionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PositionToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function __invoke(PositionToggleRequest $request, int $positionId)
    {
        try {
            $positionId = new PositionId($positionId ?? null);
            $positionToggleUseCase = new PositionToggleUseCase($this->positionRepository);
            $position = new PositionResource($positionToggleUseCase->__invoke($request, $positionId));
            return $this->successResponse(
                $position,
                'El permiso fue asignado/removido al perfil de cargo exitosamente.',
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
