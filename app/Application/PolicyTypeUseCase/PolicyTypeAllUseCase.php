<?php

declare(strict_types=1);

namespace App\Application\PolicyTypeUseCase;

use App\Domain\PolicyType\Contracts\PolicyTypeContract;
use App\Http\Requests\PolicyType\PolicyTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PolicyTypeAllUseCase
{
    private $policyTypeContract;

    public function __construct(PolicyTypeContract $policyTypeContract)
    {
        $this->policyTypeContract = $policyTypeContract;
    }

    public function __invoke(PolicyTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->policyTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
