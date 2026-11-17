<?php

declare(strict_types=1);

namespace App\Application\HealthEntityUseCase;

use App\Domain\HealthEntity\Contracts\HealthEntityContract;
use App\Http\Requests\HealthEntity\HealthEntityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class HealthEntityAllUseCase
{
    private $healthEntityContract;

    public function __construct(HealthEntityContract $healthEntityContract)
    {
        $this->healthEntityContract = $healthEntityContract;
    }

    public function __invoke(HealthEntityAllRequest $request): LengthAwarePaginator
    {
        return $this->healthEntityContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
