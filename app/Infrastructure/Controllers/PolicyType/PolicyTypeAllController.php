<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PolicyType;

use App\Application\PolicyTypeUseCase\PolicyTypeAllUseCase;
use App\Http\Requests\PolicyType\PolicyTypeAllRequest;
use App\Http\Resources\PolicyType\PolicyTypeCollection;
use App\Infrastructure\Repositories\PolicyTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PolicyTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $policyTypeRepository;

    public function __construct(PolicyTypeRepository $policyTypeRepository)
    {
        $this->policyTypeRepository = $policyTypeRepository;
    }

    public function __invoke(PolicyTypeAllRequest $request)
    {
        try {
            $policyTypeAllUseCase = new PolicyTypeAllUseCase($this->policyTypeRepository);
            $policyTypes = new PolicyTypeCollection($policyTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $policyTypes,
                'Los tipos de polizas consultados exitosamente.',
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
