<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Contracts;

use App\Domain\Location\Country\Country;
use App\Domain\Location\Country\Values\CountryId;
use App\Http\Requests\Location\Country\CountryAllRequest;
use App\Http\Requests\Location\Country\CountryFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface CountryContract
{
    public function all(?string $search, ?int $perPage, CountryAllRequest $request): LengthAwarePaginator;

    public function find(CountryFindRequest $request, CountryId $countryId): ?Country;

    public function update(Country $country, CountryId $countryId): ?Country;
}
