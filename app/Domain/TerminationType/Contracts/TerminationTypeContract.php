<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Contracts;

use App\Domain\TerminationType\TerminationType;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\TerminationTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface TerminationTypeContract
{
    public function all(string|null $search, int|null $perPage, TerminationTypeAllRequest $request): LengthAwarePaginator;

    public function find(TerminationTypeId $terminationTypeId): ?TerminationType;

    public function store(TerminationType $terminationType): TerminationType;

    public function update(TerminationType $terminationType, TerminationTypeId $terminationTypeId): TerminationType;

    public function delete(TerminationTypeId $terminationTypeId): TerminationType;

    public function restore(TerminationTypeId $terminationTypeId): TerminationType;
}
