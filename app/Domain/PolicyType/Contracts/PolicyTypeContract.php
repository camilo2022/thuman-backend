<?php

declare(strict_types=1);

namespace App\Domain\PolicyType\Contracts;

use App\Domain\PolicyType\PolicyType;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Http\Requests\PolicyType\PolicyTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface PolicyTypeContract
{
    public function all(string|null $search, int|null $perPage, PolicyTypeAllRequest $request): LengthAwarePaginator;

    public function find(PolicyTypeId $policyTypeId): ?PolicyType;

    public function store(PolicyType $policyType): PolicyType;

    public function update(PolicyType $policyType, PolicyTypeId $policyTypeId): PolicyType;

    public function delete(PolicyTypeId $policyTypeId): PolicyType;

    public function restore(PolicyTypeId $policyTypeId): PolicyType;
}
