<?php

declare(strict_types=1);

namespace App\Domain\EntityType\Contracts;

use App\Domain\EntityType\EntityType;
use App\Domain\EntityType\Values\EntityTypeId;
use App\Http\Requests\EntityType\EntityTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface EntityTypeContract
{
    public function all(string|null $search, int|null $perPage, EntityTypeAllRequest $request): LengthAwarePaginator;

    public function find(EntityTypeId $entityTypeId): ?EntityType;

    public function store(EntityType $entityType): EntityType;

    public function update(EntityType $entityType, EntityTypeId $entityTypeId): EntityType;

    public function toggle(EntityType $entityType, EntityTypeId $entityTypeId): EntityType;

    public function delete(EntityTypeId $entityTypeId): EntityType;

    public function restore(EntityTypeId $entityTypeId): EntityType;
}
