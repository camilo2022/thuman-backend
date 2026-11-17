<?php

declare(strict_types=1);

namespace App\Application\HealthEntityUseCase;

use App\Domain\HealthEntity\HealthEntity;
use App\Domain\HealthEntity\Contracts\HealthEntityContract;
use App\Domain\HealthEntity\Values\HealthEntityId;

final class HealthEntityDeleteUseCase
{
    private $healthEntityContract;

    public function __construct(HealthEntityContract $healthEntityContract)
    {
        $this->healthEntityContract = $healthEntityContract;
    }

    public function __invoke(HealthEntityId $healthEntityId): ?HealthEntity
    {
        return $this->healthEntityContract->delete($healthEntityId);
    }
}
