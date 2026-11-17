<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ServiceType;

use App\Application\ServiceTypeUseCase\ServiceTypeAllUseCase;
use App\Http\Requests\ServiceType\ServiceTypeAllRequest;
use App\Http\Resources\ServiceType\ServiceTypeCollection;
use App\Infrastructure\Repositories\ServiceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ServiceTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $serviceTypeRepository;

    public function __construct(ServiceTypeRepository $serviceTypeRepository)
    {
        $this->serviceTypeRepository = $serviceTypeRepository;
    }

    public function __invoke(ServiceTypeAllRequest $request)
    {
        try {
            $serviceTypeAllUseCase = new ServiceTypeAllUseCase($this->serviceTypeRepository);
            $serviceTypes = new ServiceTypeCollection($serviceTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $serviceTypes,
                'El tipos de servicio consultados exitosamente.',
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
