<?php

declare(strict_types=1);

namespace App\Domain\Sex\Contracts;

use App\Domain\Sex\Sex;
use App\Domain\Sex\Values\SexId;
use Illuminate\Pagination\LengthAwarePaginator;

interface SexContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(SexId $sexId): ?Sex;
}
