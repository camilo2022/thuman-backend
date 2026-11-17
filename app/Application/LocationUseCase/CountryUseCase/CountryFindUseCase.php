<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\CountryUseCase;

use App\Domain\Location\Country\Contracts\CountryContract;
use App\Domain\Location\Country\Country;
use App\Domain\Location\Country\Values\CountryId;
use App\Http\Requests\Location\Country\CountryFindRequest;

final class CountryFindUseCase
{
    private $countryContract;

    public function __construct(CountryContract $countryContract)
    {
        $this->countryContract = $countryContract;
    }

    public function __invoke(CountryFindRequest $request, CountryId $regionId): ?Country
    {
        return $this->countryContract->find($request, $regionId);
    }
}
