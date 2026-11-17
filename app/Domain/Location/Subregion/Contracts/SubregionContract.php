<?php

declare(strict_types=1);

namespace App\Domain\Location\Subregion\Contracts;

use App\Domain\Location\Subregion\Subregion;
use App\Domain\Location\Subregion\Values\SubregionId;
use App\Http\Requests\Location\Subregion\SubregionAllRequest;
use App\Http\Requests\Location\Subregion\SubregionFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface SubregionContract
{
    public function all(?string $search, ?int $perPage, SubregionAllRequest $request): LengthAwarePaginator;

    public function find(SubregionFindRequest $request, SubregionId $subregionId): ?Subregion;
}
