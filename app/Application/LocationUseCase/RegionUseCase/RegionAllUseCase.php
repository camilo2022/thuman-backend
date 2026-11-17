<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\RegionUseCase;

use App\Domain\Location\Region\Contracts\RegionContract;
use App\Http\Requests\Location\Region\RegionAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class RegionAllUseCase
{
    private $regionContract;

    public function __construct(RegionContract $regionContract)
    {
        $this->regionContract = $regionContract;
    }

    public function __invoke(RegionAllRequest $request): LengthAwarePaginator
    {
        return $this->regionContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
