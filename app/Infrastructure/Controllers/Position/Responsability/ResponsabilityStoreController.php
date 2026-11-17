<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\Responsability;

use App\Application\PositionUseCase\ResponsabilityUseCase\ResponsabilityStoreUseCase;
use App\Http\Requests\Position\Responsability\ResponsabilityStoreRequest;
use App\Http\Resources\Position\Responsability\ResponsabilityResource;
use App\Infrastructure\Repositories\ResponsabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResponsabilityStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $responsabilityRepository;

    public function __construct(ResponsabilityRepository $responsabilityRepository)
    {
        $this->responsabilityRepository = $responsabilityRepository;
    }

    public function __invoke(ResponsabilityStoreRequest $request)
    {
        try {
            $responsabilityStoreUseCase = new ResponsabilityStoreUseCase($this->responsabilityRepository);
            $responsability = new ResponsabilityResource($responsabilityStoreUseCase->__invoke($request));
            return $this->successResponse(
                $responsability,
                'La responsabilidad fue registrado exitosamente.',
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
