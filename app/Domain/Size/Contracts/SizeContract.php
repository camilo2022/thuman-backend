<?php

declare(strict_types=1);

namespace App\Domain\Size\Contracts;

use App\Domain\Size\Size;
use App\Domain\Size\Values\SizeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface SizeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(SizeId $sizeId): ?Size;
}
