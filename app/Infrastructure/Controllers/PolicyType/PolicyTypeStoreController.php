<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PolicyType;

use App\Application\PolicyTypeUseCase\PolicyTypeStoreUseCase;
use App\Http\Requests\PolicyType\PolicyTypeStoreRequest;
use App\Http\Resources\PolicyType\PolicyTypeResource;
use App\Infrastructure\Repositories\PolicyTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $policyTypeRepository;

    public function __construct(PolicyTypeRepository $policyTypeRepository)
    {
        $this->policyTypeRepository = $policyTypeRepository;
    }

    public function __invoke(PolicyTypeStoreRequest $request)
    {
        try {
            $policyTypeStoreUseCase = new PolicyTypeStoreUseCase($this->policyTypeRepository);
            $policyType = new PolicyTypeResource($policyTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $policyType,
                'El tipo de poliza fue registrado exitosamente.',
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
