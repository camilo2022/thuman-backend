<?php

declare(strict_types=1);

namespace App\Domain\SeveranceType\Contracts;

use App\Domain\SeveranceType\SeveranceType;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Http\Requests\SeveranceType\SeveranceTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface SeveranceTypeContract
{
    public function all(string|null $search, int|null $perPage, SeveranceTypeAllRequest $request): LengthAwarePaginator;

    public function find(SeveranceTypeId $severanceTypeId): ?SeveranceType;

    public function store(SeveranceType $severanceType): SeveranceType;

    public function update(SeveranceType $severanceType, SeveranceTypeId $severanceTypeId): SeveranceType;

    public function delete(SeveranceTypeId $severanceTypeId): SeveranceType;

    public function restore(SeveranceTypeId $severanceTypeId): SeveranceType;
}
