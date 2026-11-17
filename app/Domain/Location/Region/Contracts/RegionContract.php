<?php

declare(strict_types=1);

namespace App\Domain\Location\Region\Contracts;

use App\Domain\Location\Region\Region;
use App\Domain\Location\Region\Values\RegionId;
use App\Http\Requests\Location\Region\RegionFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface RegionContract
{
    public function all(?string $search, ?int $perPage): LengthAwarePaginator;

    public function find(RegionFindRequest $request, RegionId $regionId): ?Region;
}
