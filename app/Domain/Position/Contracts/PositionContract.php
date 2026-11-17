<?php

declare(strict_types=1);

namespace App\Domain\Position\Contracts;

use App\Domain\Position\Position;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\PositionAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface PositionContract
{
    public function all(string|null $search, int|null $perPage, PositionAllRequest $request): LengthAwarePaginator;

    public function find(PositionId $positionId): ?Position;

    public function store(Position $position): Position;

    public function update(Position $position, PositionId $positionId): Position;

    public function toggle(Position $position, PositionId $positionId): Position;

    public function delete(PositionId $positionId): Position;

    public function restore(PositionId $positionId): Position;
}
