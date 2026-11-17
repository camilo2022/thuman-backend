<?php

declare(strict_types=1);

namespace App\Domain\Gender\Contracts;

use App\Domain\Gender\Gender;
use App\Domain\Gender\Values\GenderId;
use Illuminate\Pagination\LengthAwarePaginator;

interface GenderContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(GenderId $genderId): ?Gender;
}
