<?php

declare(strict_types=1);

namespace App\Application\SeveranceTypeUseCase;

use App\Domain\SeveranceType\SeveranceType;
use App\Domain\SeveranceType\Contracts\SeveranceTypeContract;
use App\Domain\SeveranceType\Values\SeveranceTypeId;

final class SeveranceTypeFindUseCase
{
    private $severanceTypeContract;

    public function __construct(SeveranceTypeContract $severanceTypeContract)
    {
        $this->severanceTypeContract = $severanceTypeContract;
    }

    public function __invoke(SeveranceTypeId $severanceTypeId): ?SeveranceType
    {
        return $this->severanceTypeContract->find($severanceTypeId);
    }
}
