<?php

declare(strict_types=1);

namespace App\Domain\BloodType\Contracts;

use App\Domain\BloodType\BloodType;
use App\Domain\BloodType\Values\BloodTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface BloodTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(BloodTypeId $bloodTypeId): ?BloodType;
}
