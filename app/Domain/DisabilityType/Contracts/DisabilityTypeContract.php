<?php

declare(strict_types=1);

namespace App\Domain\DisabilityType\Contracts;

use App\Domain\DisabilityType\DisabilityType;
use App\Domain\DisabilityType\Values\DisabilityTypeId;
use App\Http\Requests\DisabilityType\DisabilityTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface DisabilityTypeContract
{
    public function all(string|null $search, int|null $perPage, DisabilityTypeAllRequest $request): LengthAwarePaginator;

    public function find(DisabilityTypeId $disabilityTypeId): ?DisabilityType;

    public function store(DisabilityType $disabilityType): DisabilityType;

    public function update(DisabilityType $disabilityType, DisabilityTypeId $disabilityTypeId): DisabilityType;

    public function delete(DisabilityTypeId $disabilityTypeId): DisabilityType;

    public function restore(DisabilityTypeId $disabilityTypeId): DisabilityType;
}
