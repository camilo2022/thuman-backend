<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PolicyType;

use App\Application\PolicyTypeUseCase\PolicyTypeRestoreUseCase;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Http\Resources\PolicyType\PolicyTypeResource;
use App\Infrastructure\Repositories\PolicyTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $policyTypeRepository;

    public function __construct(PolicyTypeRepository $policyTypeRepository)
    {
        $this->policyTypeRepository = $policyTypeRepository;
    }

    public function __invoke(int $policyTypeId)
    {
        try {
            $policyTypeId = new PolicyTypeId($policyTypeId);
            $policyTypeRestoreUseCase = new PolicyTypeRestoreUseCase($this->policyTypeRepository);
            $policyType = new PolicyTypeResource($policyTypeRestoreUseCase->__invoke($policyTypeId));
            return $this->successResponse(
                $policyType,
                'El tipo de poliza fue activada exitosamente.',
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
