<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PolicyType;

use App\Application\PolicyTypeUseCase\PolicyTypeDeleteUseCase;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Http\Resources\PolicyType\PolicyTypeResource;
use App\Infrastructure\Repositories\PolicyTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PolicyTypeDeleteController
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
            $policyTypeDeleteUseCase = new PolicyTypeDeleteUseCase($this->policyTypeRepository);
            $policyType = new PolicyTypeResource($policyTypeDeleteUseCase->__invoke($policyTypeId));
            return $this->successResponse(
                $policyType,
                'El tipo de poliza fue inactivada exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
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
