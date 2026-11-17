<?php

declare(strict_types=1);

namespace App\Domain\HealthEntity\Contracts;

use App\Domain\HealthEntity\HealthEntity;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Http\Requests\HealthEntity\HealthEntityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface HealthEntityContract
{
    public function all(string|null $search, int|null $perPage, HealthEntityAllRequest $request): LengthAwarePaginator;

    public function find(HealthEntityId $healthEntityId): ?HealthEntity;

    public function store(HealthEntity $healthEntity): HealthEntity;

    public function update(HealthEntity $healthEntity, HealthEntityId $healthEntityId): HealthEntity;

    public function toggle(HealthEntity $healthEntity, HealthEntityId $healthEntityId): HealthEntity;

    public function delete(HealthEntityId $healthEntityId): HealthEntity;

    public function restore(HealthEntityId $healthEntityId): HealthEntity;
}
