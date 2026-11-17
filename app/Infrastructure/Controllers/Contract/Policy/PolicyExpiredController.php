<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Policy;

use App\Application\ContractUseCase\PolicyUseCase\PolicyExpiredUseCase;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Http\Requests\Contract\Policy\PolicyExpiredRequest;
use App\Http\Resources\Contract\Policy\PolicyResource;
use App\Infrastructure\Repositories\PolicyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyExpiredController
{
    use ApiResponser;
    use ApiMessage;
    private $policyRepository;

    public function __construct(PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function __invoke(PolicyExpiredRequest $request, int $policyId)
    {
        try {
            $policyId = new PolicyId($policyId ?? null);
            $policyExpiredUseCase = new PolicyExpiredUseCase($this->policyRepository);
            $policy = new PolicyResource($policyExpiredUseCase->__invoke($request, $policyId));
            return $this->successResponse(
                $policy,
                'La póliza fue actualizada exitosamente a: VENCIDA.',
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
