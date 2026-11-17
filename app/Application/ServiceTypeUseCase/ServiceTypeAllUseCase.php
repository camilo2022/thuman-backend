<?php

declare(strict_types=1);

namespace App\Application\ServiceTypeUseCase;

use App\Domain\ServiceType\Contracts\ServiceTypeContract;
use App\Http\Requests\ServiceType\ServiceTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ServiceTypeAllUseCase
{
    private $serviceTypeContract;

    public function __construct(ServiceTypeContract $serviceTypeContract)
    {
        $this->serviceTypeContract = $serviceTypeContract;
    }

    public function __invoke(ServiceTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->serviceTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
