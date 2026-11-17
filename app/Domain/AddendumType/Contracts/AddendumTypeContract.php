<?php

declare(strict_types=1);

namespace App\Domain\AddendumType\Contracts;

use App\Domain\AddendumType\AddendumType;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Http\Requests\AddendumType\AddendumTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface AddendumTypeContract
{
    public function all(string|null $search, int|null $perPage, AddendumTypeAllRequest $request): LengthAwarePaginator;

    public function find(AddendumTypeId $addendumTypeId): ?AddendumType;

    public function store(AddendumType $addendumType): AddendumType;

    public function update(AddendumType $addendumType, AddendumTypeId $addendumTypeId): AddendumType;

    public function delete(AddendumTypeId $addendumTypeId): AddendumType;

    public function restore(AddendumTypeId $addendumTypeId): AddendumType;
}
