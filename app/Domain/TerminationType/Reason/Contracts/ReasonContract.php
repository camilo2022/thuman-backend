<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Reason\Contracts;

use App\Domain\TerminationType\Reason\Reason;
use App\Domain\TerminationType\Reason\Values\ReasonId;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\Reason\ReasonAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReasonContract
{
    public function all(string|null $search, int|null $perPage, ReasonAllRequest $request, TerminationTypeId $terminationTypeId): LengthAwarePaginator;

    public function find(ReasonId $reasonId): ?Reason;

    public function store(Reason $reason): Reason;

    public function update(Reason $reason, ReasonId $reasonId): Reason;

    public function delete(ReasonId $reasonId): Reason;

    public function restore(ReasonId $reasonId): Reason;
}
