<?php

declare(strict_types=1);

namespace App\Domain\Resignation\Contracts;

use App\Domain\Resignation\Resignation;
use App\Domain\Resignation\Values\ResignationId;
use App\Http\Requests\Resignation\ResignationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ResignationContract
{
    public function all(string|null $search, int|null $perPage, ResignationAllRequest $request): LengthAwarePaginator;

    public function find(ResignationId $resignationId): ?Resignation;

    public function store(Resignation $resignation): Resignation;

    public function update(Resignation $resignation, ResignationId $resignationId): Resignation;
}
