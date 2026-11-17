<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DisabilityType;

use App\Application\DisabilityTypeUseCase\DisabilityTypeStoreUseCase;
use App\Http\Requests\DisabilityType\DisabilityTypeStoreRequest;
use App\Http\Resources\DisabilityType\DisabilityTypeResource;
use App\Infrastructure\Repositories\DisabilityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityTypeRepository;

    public function __construct(DisabilityTypeRepository $disabilityTypeRepository)
    {
        $this->disabilityTypeRepository = $disabilityTypeRepository;
    }

    public function __invoke(DisabilityTypeStoreRequest $request)
    {
        try {
            $disabilityTypeStoreUseCase = new DisabilityTypeStoreUseCase($this->disabilityTypeRepository);
            $disabilityType = new DisabilityTypeResource($disabilityTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $disabilityType,
                'El tipo de incapacidad fue registrado exitosamente.',
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
