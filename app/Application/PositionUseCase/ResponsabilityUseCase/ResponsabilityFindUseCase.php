<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\ResponsabilityUseCase;

use App\Domain\Position\Responsability\Responsability;
use App\Domain\Position\Responsability\Contracts\ResponsabilityContract;
use App\Domain\Position\Responsability\Values\ResponsabilityId;

final class ResponsabilityFindUseCase
{
    private $responsabilityContract;

    public function __construct(ResponsabilityContract $responsabilityContract)
    {
        $this->responsabilityContract = $responsabilityContract;
    }

    public function __invoke(ResponsabilityId $responsabilityId): ?Responsability
    {
        return $this->responsabilityContract->find($responsabilityId);
    }
}
