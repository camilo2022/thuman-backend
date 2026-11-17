<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Policy;

use App\Application\ContractUseCase\PolicyUseCase\PolicyStoreUseCase;
use App\Http\Requests\Contract\Policy\PolicyStoreRequest;
use App\Http\Resources\Contract\Policy\PolicyResource;
use App\Infrastructure\Repositories\PolicyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $policyRepository;

    public function __construct(PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function __invoke(PolicyStoreRequest $request)
    {
        try {
            $policyStoreUseCase = new PolicyStoreUseCase($this->policyRepository);
            $policy = new PolicyResource($policyStoreUseCase->__invoke($request));
            return $this->successResponse(
                $policy,
                'La póliza registrado exitosamente.',
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
