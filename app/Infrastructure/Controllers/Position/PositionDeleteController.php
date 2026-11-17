<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position;

use App\Application\PositionUseCase\PositionDeleteUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Resources\Position\PositionResource;
use App\Infrastructure\Repositories\PositionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PositionDeleteController
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
            $positionDeleteUseCase = new PositionDeleteUseCase($this->positionRepository);
            $position = new PositionResource($positionDeleteUseCase->__invoke($positionId));
            return $this->successResponse(
                $position,
                'El perfil de cargo fue inactivado exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => 500
                ],
                500
            );
        }
    }
}
