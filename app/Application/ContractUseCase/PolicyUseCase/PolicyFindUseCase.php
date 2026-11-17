<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\PolicyUseCase;

use App\Domain\Contract\Policy\Policy;
use App\Domain\Contract\Policy\Contracts\PolicyContract;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Http\Requests\Contract\Policy\PolicyFindRequest;

final class PolicyFindUseCase
{
    private $policyContract;

    public function __construct(PolicyContract $policyContract)
    {
        $this->policyContract = $policyContract;
    }

    public function __invoke(PolicyFindRequest $request, PolicyId $policyId): ?Policy
    {
        return $this->policyContract->find($request, $policyId);
    }
}
