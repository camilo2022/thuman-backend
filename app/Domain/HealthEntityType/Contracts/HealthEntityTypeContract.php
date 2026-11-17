<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityType\Contracts;

use App\Domain\HealthEntityType\HealthEntityType;
use App\Domain\HealthEntityType\Values\HealthEntityTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface HealthEntityTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(HealthEntityTypeId $healthEntityTypeId): ?HealthEntityType;
}
