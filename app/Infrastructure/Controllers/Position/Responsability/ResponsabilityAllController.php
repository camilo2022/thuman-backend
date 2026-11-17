<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\Responsability;

use App\Application\PositionUseCase\ResponsabilityUseCase\ResponsabilityAllUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\Responsability\ResponsabilityAllRequest;
use App\Http\Resources\Position\Responsability\ResponsabilityCollection;
use App\Infrastructure\Repositories\ResponsabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResponsabilityAllController
{
    use ApiResponser;
    use ApiMessage;
    private $responsabilityRepository;

    public function __construct(ResponsabilityRepository $responsabilityRepository)
    {
        $this->responsabilityRepository = $responsabilityRepository;
    }

    public function __invoke(ResponsabilityAllRequest $request, int $positionId)
    {
        try {
            $positionId = new PositionId($positionId ?? null);
            $responsabilityAllUseCase = new ResponsabilityAllUseCase($this->responsabilityRepository);
            $responsabilitys = new ResponsabilityCollection($responsabilityAllUseCase->__invoke($request, $positionId));
            return $this->successResponse(
                $responsabilitys,
                'Las responsabilidades fueron consultados exitosamente.',
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
