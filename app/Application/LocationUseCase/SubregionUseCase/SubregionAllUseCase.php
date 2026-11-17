<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\SubregionUseCase;

use App\Domain\Location\Subregion\Contracts\SubregionContract;
use App\Http\Requests\Location\Subregion\SubregionAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SubregionAllUseCase
{
    private $regionContract;

    public function __construct(SubregionContract $regionContract)
    {
        $this->regionContract = $regionContract;
    }

    public function __invoke(SubregionAllRequest $request): LengthAwarePaginator
    {
        return $this->regionContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
