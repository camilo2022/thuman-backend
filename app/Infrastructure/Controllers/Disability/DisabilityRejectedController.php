<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Disability;

use App\Application\DisabilityUseCase\DisabilityRejectedUseCase;
use App\Domain\Disability\Values\DisabilityId;
use App\Http\Requests\Disability\DisabilityRejectedRequest;
use App\Http\Resources\Disability\DisabilityResource;
use App\Infrastructure\Repositories\DisabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityRepository;

    public function __construct(DisabilityRepository $disabilityRepository)
    {
        $this->disabilityRepository = $disabilityRepository;
    }

    public function __invoke(DisabilityRejectedRequest $request, int $disabilityId)
    {
        try {
            $disabilityId = new DisabilityId($disabilityId ?? null);
            $disabilityRejectedUseCase = new DisabilityRejectedUseCase($this->disabilityRepository);
            $disability = new DisabilityResource($disabilityRejectedUseCase->__invoke($request, $disabilityId));
            return $this->successResponse(
                $disability,
                'El reporte de incapacidad fue actualizada exitosamente a: RECHAZADO.',
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
