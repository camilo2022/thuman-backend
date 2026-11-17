<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ServiceType;

use App\Application\ServiceTypeUseCase\ServiceTypeFindUseCase;
use App\Domain\ServiceType\Values\ServiceTypeId;
use App\Http\Resources\ServiceType\ServiceTypeResource;
use App\Infrastructure\Repositories\ServiceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ServiceTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $serviceTypeRepository;

    public function __construct(ServiceTypeRepository $serviceTypeRepository)
    {
        $this->serviceTypeRepository = $serviceTypeRepository;
    }

    public function __invoke(int $serviceTypeId)
    {
        try {
            $serviceTypeId = new ServiceTypeId($serviceTypeId ?? null);
            $serviceTypeFindUseCase = new ServiceTypeFindUseCase($this->serviceTypeRepository);
            $serviceType = new ServiceTypeResource($serviceTypeFindUseCase->__invoke($serviceTypeId));
            return $this->successResponse(
                $serviceType,
                'Los motivos solicitud de personal fue encontrada exitosamente.',
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
