<?php

declare(strict_types=1);

namespace App\Application\ServiceProviderUseCase;

use App\Domain\ServiceProvider\Contracts\ServiceProviderContract;
use App\Domain\ServiceProvider\ServiceProvider;
use App\Domain\ServiceProvider\Values\ServiceProviderCountryId;
use App\Domain\ServiceProvider\Values\ServiceProviderServiceTypeId;
use App\Domain\ServiceProvider\Values\ServiceProviderDescription;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Domain\ServiceProvider\Values\ServiceProviderItemId;
use App\Domain\ServiceProvider\Values\ServiceProviderName;
use App\Domain\ServiceProvider\Values\ServiceProviderSettings;
use App\Http\Requests\ServiceProvider\ServiceProviderToggleRequest;

final class ServiceProviderToggleUseCase
{
    private $serviceProviderContract;

    public function __construct(ServiceProviderContract $serviceProviderContract)
    {
        $this->serviceProviderContract = $serviceProviderContract;
    }

    public function __invoke(ServiceProviderToggleRequest $request, ServiceProviderId $serviceProviderId): ServiceProvider
    {
        return $this->serviceProviderContract->toggle(
            new ServiceProvider(
                new ServiceProviderId($serviceProviderId->value()),
                new ServiceProviderItemId($request->integer('item_id')),
                new ServiceProviderName($request->input('name')),
                new ServiceProviderDescription($request->input('description')),
                new ServiceProviderSettings((object) []),
                new ServiceProviderServiceTypeId($request->integer('service_type_id')),
                new ServiceProviderCountryId($request->integer('country_id'))
            ),
            $serviceProviderId
        );
    }
}
