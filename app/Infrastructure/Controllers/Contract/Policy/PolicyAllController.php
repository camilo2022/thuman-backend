<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Policy;

use App\Application\ContractUseCase\PolicyUseCase\PolicyAllUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Policy\PolicyAllRequest;
use App\Http\Resources\Contract\Policy\PolicyCollection;
use App\Infrastructure\Repositories\PolicyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyAllController
{
    use ApiResponser;
    use ApiMessage;
    private $policyRepository;

    public function __construct(PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function __invoke(PolicyAllRequest $request, int $contractId)
    {
        try {
            $contractId = new ContractId($contractId);
            $policyAllUseCase = new PolicyAllUseCase($this->policyRepository);
            $policies = new PolicyCollection($policyAllUseCase->__invoke($request, $contractId));
            return $this->successResponse(
                $policies,
                'Las pólizas consultadas exitosamente.',
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
