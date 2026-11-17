<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\CountryUseCase;

use App\Domain\Location\Country\Contracts\CountryContract;
use App\Http\Requests\Location\Country\CountryAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CountryAllUseCase
{
    private $countryContract;

    public function __construct(CountryContract $countryContract)
    {
        $this->countryContract = $countryContract;
    }

    public function __invoke(CountryAllRequest $request): LengthAwarePaginator
    {
        return $this->countryContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
