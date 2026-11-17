<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\Responsability;

use App\Application\PositionUseCase\ResponsabilityUseCase\ResponsabilityFindUseCase;
use App\Domain\Position\Responsability\Values\ResponsabilityId;
use App\Http\Resources\Position\Responsability\ResponsabilityResource;
use App\Infrastructure\Repositories\ResponsabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResponsabilityFindController
{
    use ApiResponser;
    use ApiMessage;
    private $responsabilityRepository;

    public function __construct(ResponsabilityRepository $responsabilityRepository)
    {
        $this->responsabilityRepository = $responsabilityRepository;
    }

    public function __invoke(int $responsabilityId)
    {
        try {
            $responsabilityId = new ResponsabilityId($responsabilityId ?? null);
            $responsabilityFindUseCase = new ResponsabilityFindUseCase($this->responsabilityRepository);
            $responsability = new ResponsabilityResource($responsabilityFindUseCase->__invoke($responsabilityId));
            return $this->successResponse(
                $responsability,
                'La responsabilidad fue encontrado exitosamente.',
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
