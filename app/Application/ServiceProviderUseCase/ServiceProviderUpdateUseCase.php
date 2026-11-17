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
use App\Http\Requests\ServiceProvider\ServiceProviderUpdateRequest;

final class ServiceProviderUpdateUseCase
{
    private $serviceProviderContract;

    public function __construct(ServiceProviderContract $serviceProviderContract)
    {
        $this->serviceProviderContract = $serviceProviderContract;
    }

    public function __invoke(ServiceProviderUpdateRequest $request, ServiceProviderId $serviceProviderId): ServiceProvider
    {
        return $this->serviceProviderContract->update(
            new ServiceProvider(
                new ServiceProviderId($serviceProviderId->value()),
                new ServiceProviderItemId($request->integer('item_id')),
                new ServiceProviderName($request->input('name')),
                new ServiceProviderDescription($request->input('description')),
                new ServiceProviderSettings((object) [])
            ),
            $serviceProviderId
        );
    }
}
