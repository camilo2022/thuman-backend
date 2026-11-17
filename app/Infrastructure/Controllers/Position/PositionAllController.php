<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position;

use App\Application\PositionUseCase\PositionAllUseCase;
use App\Http\Requests\Position\PositionAllRequest;
use App\Http\Resources\Position\PositionCollection;
use App\Infrastructure\Repositories\PositionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PositionAllController
{
    use ApiResponser;
    use ApiMessage;
    private $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function __invoke(PositionAllRequest $request)
    {
        try {
            $positionAllUseCase = new PositionAllUseCase($this->positionRepository);
            $positions = new PositionCollection($positionAllUseCase->__invoke($request));
            return $this->successResponse(
                $positions,
                'Los perfiles de cargo consultados exitosamente.',
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
