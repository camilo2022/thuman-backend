<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PolicyType;

use App\Application\PolicyTypeUseCase\PolicyTypeUpdateUseCase;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Http\Requests\PolicyType\PolicyTypeUpdateRequest;
use App\Http\Resources\PolicyType\PolicyTypeResource;
use App\Infrastructure\Repositories\PolicyTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $policyTypeRepository;

    public function __construct(PolicyTypeRepository $policyTypeRepository)
    {
        $this->policyTypeRepository = $policyTypeRepository;
    }

    public function __invoke(PolicyTypeUpdateRequest $request, int $policyTypeId)
    {
        try {
            $policyTypeId = new PolicyTypeId($policyTypeId ?? null);
            $policyTypeUpdateUseCase = new PolicyTypeUpdateUseCase($this->policyTypeRepository);
            $policyType = new PolicyTypeResource($policyTypeUpdateUseCase->__invoke($request, $policyTypeId));
            return $this->successResponse(
                $policyType,
                'El tipo de poliza fue actualizado exitosamente.',
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
