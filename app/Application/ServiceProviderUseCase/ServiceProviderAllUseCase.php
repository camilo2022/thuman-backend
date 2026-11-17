<?php

declare(strict_types=1);

namespace App\Application\ServiceProviderUseCase;

use App\Domain\ServiceProvider\Contracts\ServiceProviderContract;
use App\Http\Requests\ServiceProvider\ServiceProviderAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ServiceProviderAllUseCase
{
    private $serviceProviderContract;

    public function __construct(ServiceProviderContract $serviceProviderContract)
    {
        $this->serviceProviderContract = $serviceProviderContract;
    }

    public function __invoke(ServiceProviderAllRequest $request): LengthAwarePaginator
    {
        return $this->serviceProviderContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
