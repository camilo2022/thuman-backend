<?php

declare(strict_types=1);

namespace App\Application\DisabilityTypeUseCase;

use App\Domain\DisabilityType\DisabilityType;
use App\Domain\DisabilityType\Contracts\DisabilityTypeContract;
use App\Domain\DisabilityType\Values\DisabilityTypeId;

final class DisabilityTypeFindUseCase
{
    private $disabilityTypeContract;

    public function __construct(DisabilityTypeContract $disabilityTypeContract)
    {
        $this->disabilityTypeContract = $disabilityTypeContract;
    }

    public function __invoke(DisabilityTypeId $disabilityTypeId): ?DisabilityType
    {
        return $this->disabilityTypeContract->find($disabilityTypeId);
    }
}
