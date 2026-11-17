<?php

declare(strict_types=1);

namespace App\Application\RelationshipUseCase;

use App\Domain\Relationship\Relationship;
use App\Domain\Relationship\Contracts\RelationshipContract;
use App\Domain\Relationship\Values\RelationshipId;

final class RelationshipFindUseCase
{
    private $relationshipContract;

    public function __construct(RelationshipContract $relationshipContract)
    {
        $this->relationshipContract = $relationshipContract;
    }

    public function __invoke(RelationshipId $relationshipId): ?Relationship
    {
        return $this->relationshipContract->find($relationshipId);
    }
}
