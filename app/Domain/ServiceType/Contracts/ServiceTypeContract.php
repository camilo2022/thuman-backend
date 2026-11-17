<?php

declare(strict_types=1);

namespace App\Domain\ServiceType\Contracts;

use App\Domain\ServiceType\ServiceType;
use App\Domain\ServiceType\Values\ServiceTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(ServiceTypeId $serviceTypeId): ?ServiceType;
}
