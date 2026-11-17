<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\CityUseCase;

use App\Domain\Location\City\Contracts\CityContract;
use App\Http\Requests\Location\City\CityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CityAllUseCase
{
    private $cityContract;

    public function __construct(CityContract $cityContract)
    {
        $this->cityContract = $cityContract;
    }

    public function __invoke(CityAllRequest $request): LengthAwarePaginator
    {
        return $this->cityContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
