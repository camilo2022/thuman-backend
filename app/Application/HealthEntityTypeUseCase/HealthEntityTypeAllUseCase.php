<?php

declare(strict_types=1);

namespace App\Application\HealthEntityTypeUseCase;

use App\Domain\HealthEntityType\Contracts\HealthEntityTypeContract;
use App\Http\Requests\HealthEntityType\HealthEntityTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class HealthEntityTypeAllUseCase
{
    private $healthEntityTypeContract;

    public function __construct(HealthEntityTypeContract $healthEntityTypeContract)
    {
        $this->healthEntityTypeContract = $healthEntityTypeContract;
    }

    public function __invoke(HealthEntityTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->healthEntityTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
