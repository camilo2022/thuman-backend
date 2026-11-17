<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Contracts;

use App\Domain\Contract\Policy\File;
use App\Domain\Contract\Policy\Policy;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Policy\PolicyAllRequest;
use App\Http\Requests\Contract\Policy\PolicyFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface PolicyContract
{
    public function all(string|null $search, int|null $perPage, PolicyAllRequest $request, ContractId $contractId): LengthAwarePaginator;

    public function find(PolicyFindRequest $request, PolicyId $policyId): ?Policy;

    public function store(Policy $policy): Policy;

    public function update(Policy $policy, PolicyId $policyId): Policy;

    public function file(File $file): File;
}
