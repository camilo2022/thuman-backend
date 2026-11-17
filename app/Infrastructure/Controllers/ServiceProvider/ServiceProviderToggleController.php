<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ServiceProvider;

use App\Application\ServiceProviderUseCase\ServiceProviderToggleUseCase;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Http\Requests\ServiceProvider\ServiceProviderToggleRequest;
use App\Http\Resources\ServiceProvider\ServiceProviderResource;
use App\Infrastructure\Repositories\ServiceProviderRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ServiceProviderToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $serviceProviderRepository;

    public function __construct(ServiceProviderRepository $serviceProviderRepository)
    {
        $this->serviceProviderRepository = $serviceProviderRepository;
    }

    public function __invoke(ServiceProviderToggleRequest $request, int $serviceProviderId)
    {
        try {
            $serviceProviderId = new ServiceProviderId($serviceProviderId ?? null);
            $serviceProviderToggleUseCase = new ServiceProviderToggleUseCase($this->serviceProviderRepository);
            $serviceProvider = new ServiceProviderResource($serviceProviderToggleUseCase->__invoke($request, $serviceProviderId));
            return $this->successResponse(
                $serviceProvider,
                'El servicio fue asignado/removido al proveedor de servicio exitosamente.',
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
