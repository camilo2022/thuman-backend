<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\Responsability;

use App\Application\PositionUseCase\ResponsabilityUseCase\ResponsabilityDeleteUseCase;
use App\Domain\Position\Responsability\Values\ResponsabilityId;
use App\Infrastructure\Repositories\ResponsabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResponsabilityDeleteController
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
            $responsabilityId = new ResponsabilityId($responsabilityId);
            $responsabilityDeleteUseCase = new ResponsabilityDeleteUseCase($this->responsabilityRepository);
            $responsabilityDeleteUseCase->__invoke($responsabilityId);
            return $this->successResponse(
                '',
                'La responsabilidad fue inactivada exitosamente.',
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
