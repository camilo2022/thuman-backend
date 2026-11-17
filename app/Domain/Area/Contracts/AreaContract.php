<?php

declare(strict_types=1);

namespace App\Domain\Area\Contracts;

use App\Domain\Area\Area;
use App\Domain\Area\Values\AreaId;
use App\Http\Requests\Area\AreaAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface AreaContract
{
    public function all(string|null $search, int|null $perPage, AreaAllRequest $request): LengthAwarePaginator;

    public function find(AreaId $areaId): ?Area;

    public function store(Area $area): Area;

    public function update(Area $area, AreaId $areaId): Area;

    public function delete(AreaId $areaId): Area;

    public function restore(AreaId $areaId): Area;
}
