<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Disability;

use App\Application\DisabilityUseCase\DisabilityFindUseCase;
use App\Domain\Disability\Values\DisabilityId;
use App\Http\Resources\Disability\DisabilityResource;
use App\Infrastructure\Repositories\DisabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityFindController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityRepository;

    public function __construct(DisabilityRepository $disabilityRepository)
    {
        $this->disabilityRepository = $disabilityRepository;
    }

    public function __invoke(int $disabilityId)
    {
        try {
            $disabilityId = new DisabilityId($disabilityId ?? null);
            $disabilityFindUseCase = new DisabilityFindUseCase($this->disabilityRepository);
            $disability = new DisabilityResource($disabilityFindUseCase->__invoke($disabilityId));
            return $this->successResponse(
                $disability,
                'El reporte de incapacidad fue encontrada exitosamente.',
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
