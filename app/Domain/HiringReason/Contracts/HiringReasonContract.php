<?php

declare(strict_types=1);

namespace App\Domain\HiringReason\Contracts;

use App\Domain\HiringReason\HiringReason;
use App\Domain\HiringReason\Values\HiringReasonId;
use Illuminate\Pagination\LengthAwarePaginator;

interface HiringReasonContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(HiringReasonId $hiringReasonId): ?HiringReason;
}
