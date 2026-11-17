<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Policy;

use App\Application\ContractUseCase\PolicyUseCase\PolicyFindUseCase;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Http\Requests\Contract\Policy\PolicyFindRequest;
use App\Http\Resources\Contract\Policy\PolicyResource;
use App\Infrastructure\Repositories\PolicyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyFindController
{
    use ApiResponser;
    use ApiMessage;
    private $policyRepository;

    public function __construct(PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function __invoke(PolicyFindRequest $request, int $policyId)
    {
        try {
            $policyId = new PolicyId($policyId ?? null);
            $policyFindUseCase = new PolicyFindUseCase($this->policyRepository);
            $policy = new PolicyResource($policyFindUseCase->__invoke($request, $policyId));
            return $this->successResponse(
                $policy,
                'La póliza fue encontrada exitosamente.',
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
