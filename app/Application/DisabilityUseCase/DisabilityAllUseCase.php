<?php

declare(strict_types=1);

namespace App\Application\DisabilityUseCase;

use App\Domain\Disability\Contracts\DisabilityContract;
use App\Http\Requests\Disability\DisabilityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class DisabilityAllUseCase
{
    private $disabilityContract;

    public function __construct(DisabilityContract $disabilityContract)
    {
        $this->disabilityContract = $disabilityContract;
    }

    public function __invoke(DisabilityAllRequest $request): LengthAwarePaginator
    {
        return $this->disabilityContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
