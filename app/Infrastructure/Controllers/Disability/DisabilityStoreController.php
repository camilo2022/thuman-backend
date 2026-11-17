<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Disability;

use App\Application\DisabilityUseCase\DisabilityStoreUseCase;
use App\Http\Requests\Disability\DisabilityStoreRequest;
use App\Http\Resources\Disability\DisabilityResource;
use App\Infrastructure\Repositories\DisabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityRepository;

    public function __construct(DisabilityRepository $disabilityRepository)
    {
        $this->disabilityRepository = $disabilityRepository;
    }

    public function __invoke(DisabilityStoreRequest $request)
    {
        try {
            $disabilityStoreUseCase = new DisabilityStoreUseCase($this->disabilityRepository);
            $disability = new DisabilityResource($disabilityStoreUseCase->__invoke($request));
            return $this->successResponse(
                $disability,
                'El reporte de incapacidad registrado exitosamente.',
                201
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
