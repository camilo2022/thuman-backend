<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase;

use App\Domain\Enterprise\Entity\Entity;
use App\Domain\Enterprise\Entity\Contracts\EntityContract;
use App\Domain\Enterprise\Entity\Values\EntityId;

final class EntityFindUseCase
{
    private $entityContract;

    public function __construct(EntityContract $entityContract)
    {
        $this->entityContract = $entityContract;
    }

    public function __invoke(EntityId $entityId): ?Entity
    {
        return $this->entityContract->find($entityId);
    }
}
