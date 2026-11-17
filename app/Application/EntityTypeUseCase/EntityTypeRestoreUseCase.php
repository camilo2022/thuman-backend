<?php

declare(strict_types=1);

namespace App\Application\EntityTypeUseCase;

use App\Domain\EntityType\EntityType;
use App\Domain\EntityType\Contracts\EntityTypeContract;
use App\Domain\EntityType\Values\EntityTypeId;

final class EntityTypeRestoreUseCase
{
    private $entityTypeContract;

    public function __construct(EntityTypeContract $entityTypeContract)
    {
        $this->entityTypeContract = $entityTypeContract;
    }

    public function __invoke(EntityTypeId $entityTypeId): ?EntityType
    {
        return $this->entityTypeContract->restore($entityTypeId);
    }
}
