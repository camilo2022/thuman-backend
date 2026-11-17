<?php

declare(strict_types=1);

namespace App\Application\ServiceProviderUseCase;

use App\Domain\ServiceProvider\ServiceProvider;
use App\Domain\ServiceProvider\Contracts\ServiceProviderContract;
use App\Domain\ServiceProvider\Values\ServiceProviderId;

final class ServiceProviderFindUseCase
{
    private $serviceProviderContract;

    public function __construct(ServiceProviderContract $serviceProviderContract)
    {
        $this->serviceProviderContract = $serviceProviderContract;
    }

    public function __invoke(ServiceProviderId $serviceProviderId): ?ServiceProvider
    {
        return $this->serviceProviderContract->find($serviceProviderId);
    }
}
