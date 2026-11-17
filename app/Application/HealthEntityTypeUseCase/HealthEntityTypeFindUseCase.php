<?php

declare(strict_types=1);

namespace App\Application\HealthEntityTypeUseCase;

use App\Domain\HealthEntityType\HealthEntityType;
use App\Domain\HealthEntityType\Contracts\HealthEntityTypeContract;
use App\Domain\HealthEntityType\Values\HealthEntityTypeId;

final class HealthEntityTypeFindUseCase
{
    private $healthEntityTypeContract;

    public function __construct(HealthEntityTypeContract $healthEntityTypeContract)
    {
        $this->healthEntityTypeContract = $healthEntityTypeContract;
    }

    public function __invoke(HealthEntityTypeId $healthEntityTypeId): ?HealthEntityType
    {
        return $this->healthEntityTypeContract->find($healthEntityTypeId);
    }
}
