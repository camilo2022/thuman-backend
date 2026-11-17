<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Contracts;

use App\Domain\Vacant\Vacant;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\VacantAllRequest;
use App\Http\Requests\Vacant\VacantFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface VacantContract
{
    public function all(string|null $search, int|null $perPage, VacantAllRequest $request): LengthAwarePaginator;

    public function find(VacantFindRequest $request, VacantId $vacantId): ?Vacant;

    public function store(Vacant $vacant): Vacant;

    public function update(Vacant $vacant, VacantId $vacantId): Vacant;
}
