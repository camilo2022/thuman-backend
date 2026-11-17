<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Policy;

use App\Application\ContractUseCase\PolicyUseCase\PolicyCanceledUseCase;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Http\Requests\Contract\Policy\PolicyCanceledRequest;
use App\Http\Resources\Contract\Policy\PolicyResource;
use App\Infrastructure\Repositories\PolicyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyCanceledController
{
    use ApiResponser;
    use ApiMessage;
    private $policyRepository;

    public function __construct(PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function __invoke(PolicyCanceledRequest $request, int $policyId)
    {
        try {
            $policyId = new PolicyId($policyId ?? null);
            $policyCanceledUseCase = new PolicyCanceledUseCase($this->policyRepository);
            $policy = new PolicyResource($policyCanceledUseCase->__invoke($request, $policyId));
            return $this->successResponse(
                $policy,
                'La póliza fue actualizada exitosamente a: CANCELADO.',
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
