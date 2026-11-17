<?php

declare(strict_types=1);

namespace App\Application\AreaUseCase;

use App\Domain\Area\Contracts\AreaContract;
use App\Http\Requests\Area\AreaAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AreaAllUseCase
{
    private $areaContract;

    public function __construct(AreaContract $areaContract)
    {
        $this->areaContract = $areaContract;
    }

    public function __invoke(AreaAllRequest $request): LengthAwarePaginator
    {
        return $this->areaContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
