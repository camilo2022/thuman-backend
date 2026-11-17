<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\RegionUseCase;

use App\Domain\Location\Region\Contracts\RegionContract;
use App\Domain\Location\Region\Region;
use App\Domain\Location\Region\Values\RegionId;
use App\Http\Requests\Location\Region\RegionFindRequest;

final class RegionFindUseCase
{
    private $regionContract;

    public function __construct(RegionContract $regionContract)
    {
        $this->regionContract = $regionContract;
    }

    public function __invoke(RegionFindRequest $request, RegionId $regionId): ?Region
    {
        return $this->regionContract->find($request, $regionId);
    }
}
