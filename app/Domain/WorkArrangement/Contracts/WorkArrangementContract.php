<?php

declare(strict_types=1);

namespace App\Domain\WorkArrangement\Contracts;

use App\Domain\WorkArrangement\WorkArrangement;
use App\Domain\WorkArrangement\Values\WorkArrangementId;
use App\Http\Requests\WorkArrangement\WorkArrangementAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface WorkArrangementContract
{
    public function all(string|null $search, int|null $perPage, WorkArrangementAllRequest $request): LengthAwarePaginator;

    public function find(WorkArrangementId $workArrangementId): ?WorkArrangement;

    public function store(WorkArrangement $workArrangement): WorkArrangement;

    public function update(WorkArrangement $workArrangement, WorkArrangementId $workArrangementId): WorkArrangement;

    public function delete(WorkArrangementId $workArrangementId): WorkArrangement;

    public function restore(WorkArrangementId $workArrangementId): WorkArrangement;
}
