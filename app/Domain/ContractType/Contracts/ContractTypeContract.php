<?php

declare(strict_types=1);

namespace App\Domain\ContractType\Contracts;

use App\Domain\ContractType\ContractType;
use App\Domain\ContractType\Values\ContractTypeId;
use App\Http\Requests\ContractType\ContractTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContractTypeContract
{
    public function all(string|null $search, int|null $perPage, ContractTypeAllRequest $request): LengthAwarePaginator;

    public function find(ContractTypeId $contractTypeId): ?ContractType;

    public function store(ContractType $contractType): ContractType;

    public function update(ContractType $contractType, ContractTypeId $contractTypeId): ContractType;

    public function delete(ContractTypeId $contractTypeId): ContractType;

    public function restore(ContractTypeId $contractTypeId): ContractType;
}
