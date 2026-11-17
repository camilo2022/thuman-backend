<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\CityUseCase;

use App\Domain\Location\City\Contracts\CityContract;
use App\Domain\Location\City\City;
use App\Domain\Location\City\Values\CityId;
use App\Http\Requests\Location\City\CityFindRequest;

final class CityFindUseCase
{
    private $cityContract;

    public function __construct(CityContract $cityContract)
    {
        $this->cityContract = $cityContract;
    }

    public function __invoke(CityFindRequest $request, CityId $regionId): ?City
    {
        return $this->cityContract->find($request, $regionId);
    }
}
