<?php

declare(strict_types=1);

namespace App\Domain\ServiceProvider\Contracts;

use App\Domain\ServiceProvider\ServiceProvider;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Http\Requests\ServiceProvider\ServiceProviderAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceProviderContract
{
    public function all(string|null $search, int|null $perPage, ServiceProviderAllRequest $request): LengthAwarePaginator;

    public function find(ServiceProviderId $serviceProviderId): ?ServiceProvider;

    public function store(ServiceProvider $serviceProvider): ServiceProvider;

    public function update(ServiceProvider $serviceProvider, ServiceProviderId $serviceProviderId): ServiceProvider;

    public function toggle(ServiceProvider $serviceProvider, ServiceProviderId $serviceProviderId): ServiceProvider;

    public function delete(ServiceProviderId $serviceProviderId): ServiceProvider;

    public function restore(ServiceProviderId $serviceProviderId): ServiceProvider;
}
