<?php

declare(strict_types=1);

namespace App\Application\ServiceProviderUseCase;

use App\Domain\ServiceProvider\ServiceProvider;
use App\Domain\ServiceProvider\Contracts\ServiceProviderContract;
use App\Domain\ServiceProvider\Values\ServiceProviderId;

final class ServiceProviderDeleteUseCase
{
    private $serviceProviderontract;

    public function __construct(ServiceProviderContract $serviceProviderontract)
    {
        $this->serviceProviderontract = $serviceProviderontract;
    }

    public function __invoke(ServiceProviderId $fiscalDocumentId): ?ServiceProvider
    {
        return $this->serviceProviderontract->delete($fiscalDocumentId);
    }
}
