<?php

declare(strict_types=1);

namespace App\Domain\WorkDay\Contracts;

use App\Domain\WorkDay\WorkDay;
use App\Domain\WorkDay\Values\WorkDayId;
use App\Http\Requests\WorkDay\WorkDayAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface WorkDayContract
{
    public function all(string|null $search, int|null $perPage, WorkDayAllRequest $request): LengthAwarePaginator;

    public function find(WorkDayId $workDayId): ?WorkDay;

    public function store(WorkDay $workDay): WorkDay;

    public function update(WorkDay $workDay, WorkDayId $workDayId): WorkDay;

    public function delete(WorkDayId $workDayId): WorkDay;

    public function restore(WorkDayId $workDayId): WorkDay;
}
