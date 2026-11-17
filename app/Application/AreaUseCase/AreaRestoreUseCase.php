<?php

declare(strict_types=1);

namespace App\Application\AreaUseCase;

use App\Domain\Area\Area;
use App\Domain\Area\Contracts\AreaContract;
use App\Domain\Area\Values\AreaId;

final class AreaRestoreUseCase
{
    private $areaContract;

    public function __construct(AreaContract $areaContract)
    {
        $this->areaContract = $areaContract;
    }

    public function __invoke(AreaId $areaId): ?Area
    {
        return $this->areaContract->restore($areaId);
    }
}
