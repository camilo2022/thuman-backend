<?php

declare(strict_types=1);

namespace App\Domain\Location\City\Contracts;

use App\Domain\Location\City\City;
use App\Domain\Location\City\Values\CityId;
use App\Http\Requests\Location\City\CityAllRequest;
use App\Http\Requests\Location\City\CityFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface CityContract
{
    public function all(?string $search, ?int $perPage, CityAllRequest $request): LengthAwarePaginator;

    public function find(CityFindRequest $request, CityId $cityId): ?City;
}
