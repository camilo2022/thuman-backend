<?php

declare(strict_types=1);

namespace App\Domain\MaritalStatus\Contracts;

use App\Domain\MaritalStatus\MaritalStatus;
use App\Domain\MaritalStatus\Values\MaritalStatusId;
use App\Http\Requests\MaritalStatus\MaritalStatusAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface MaritalStatusContract
{
    public function all(string|null $search, int|null $perPage, MaritalStatusAllRequest $request): LengthAwarePaginator;

    public function find(MaritalStatusId $maritalStatusId): ?MaritalStatus;

    public function store(MaritalStatus $maritalStatus): MaritalStatus;

    public function update(MaritalStatus $maritalStatus, MaritalStatusId $maritalStatusId): MaritalStatus;

    public function delete(MaritalStatusId $maritalStatusId): MaritalStatus;

    public function restore(MaritalStatusId $maritalStatusId): MaritalStatus;
}
