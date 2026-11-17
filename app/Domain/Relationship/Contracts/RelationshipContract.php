<?php

declare(strict_types=1);

namespace App\Domain\Relationship\Contracts;

use App\Domain\Relationship\Relationship;
use App\Domain\Relationship\Values\RelationshipId;
use Illuminate\Pagination\LengthAwarePaginator;

interface RelationshipContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(RelationshipId $relationshipId): ?Relationship;
}
