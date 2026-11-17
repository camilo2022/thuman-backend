<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position;

use App\Application\PositionUseCase\PositionRestoreUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Resources\Position\PositionResource;
use App\Infrastructure\Repositories\PositionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PositionRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function __invoke(int $positionId)
    {
        try {
            $positionId = new PositionId($positionId);
            $positionRestoreUseCase = new PositionRestoreUseCase($this->positionRepository);
            $position = new PositionResource($positionRestoreUseCase->__invoke($positionId));
            return $this->successResponse(
                $position,
                'El perfil de cargo fue activada exitosamente.',
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
