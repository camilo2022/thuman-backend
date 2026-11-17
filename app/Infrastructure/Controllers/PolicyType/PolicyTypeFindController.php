<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PolicyType;

use App\Application\PolicyTypeUseCase\PolicyTypeFindUseCase;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Http\Resources\PolicyType\PolicyTypeResource;
use App\Infrastructure\Repositories\PolicyTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyTypeFindController
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
            $policyTypeId = new PolicyTypeId($policyTypeId ?? null);
            $policyTypeFindUseCase = new PolicyTypeFindUseCase($this->policyTypeRepository);
            $policyType = new PolicyTypeResource($policyTypeFindUseCase->__invoke($policyTypeId));
            return $this->successResponse(
                $policyType,
                'El tipo de poliza fue encontrada exitosamente.',
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
