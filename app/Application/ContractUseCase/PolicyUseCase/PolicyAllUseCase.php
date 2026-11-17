<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\PolicyUseCase;

use App\Domain\Contract\Policy\Contracts\PolicyContract;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Policy\PolicyAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PolicyAllUseCase
{
    private $policyContract;

    public function __construct(PolicyContract $policyContract)
    {
        $this->policyContract = $policyContract;
    }

    public function __invoke(PolicyAllRequest $request, ContractId $contractId): LengthAwarePaginator
    {
        return $this->policyContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request, $contractId);
    }
}
