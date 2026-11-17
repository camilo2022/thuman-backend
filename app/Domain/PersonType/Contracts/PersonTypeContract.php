<?php

declare(strict_types=1);

namespace App\Domain\PersonType\Contracts;

use App\Domain\PersonType\PersonType;
use App\Domain\PersonType\Values\PersonTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface PersonTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(PersonTypeId $personTypeId): ?PersonType;
}
