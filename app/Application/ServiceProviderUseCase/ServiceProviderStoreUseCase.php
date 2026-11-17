<?php

declare(strict_types=1);

namespace App\Application\ServiceProviderUseCase;

use App\Domain\ServiceProvider\Contracts\ServiceProviderContract;
use App\Domain\ServiceProvider\ServiceProvider;
use App\Domain\ServiceProvider\Values\ServiceProviderDescription;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Domain\ServiceProvider\Values\ServiceProviderItemId;
use App\Domain\ServiceProvider\Values\ServiceProviderName;
use App\Domain\ServiceProvider\Values\ServiceProviderSettings;
use App\Http\Requests\ServiceProvider\ServiceProviderStoreRequest;

final class ServiceProviderStoreUseCase
{
    private $serviceProviderContract;

    public function __construct(ServiceProviderContract $serviceProviderContract)
    {
        $this->serviceProviderContract = $serviceProviderContract;
    }

    public function __invoke(ServiceProviderStoreRequest $request): ServiceProvider
    {
        return $this->serviceProviderContract->store(
            new ServiceProvider(
                new ServiceProviderId(null),
                new ServiceProviderItemId($request->integer('item_id')),
                new ServiceProviderName($request->input('name')),
                new ServiceProviderDescription($request->input('description')),
                new ServiceProviderSettings((object) [])
            )
        );
    }
}
