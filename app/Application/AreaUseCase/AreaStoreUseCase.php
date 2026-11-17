<?php

declare(strict_types=1);

namespace App\Application\AreaUseCase;

use App\Domain\Area\Contracts\AreaContract;
use App\Domain\Area\Area;
use App\Domain\Area\Values\AreaDescription;
use App\Domain\Area\Values\AreaId;
use App\Domain\Area\Values\AreaName;
use App\Http\Requests\Area\AreaStoreRequest;

final class AreaStoreUseCase
{
    private $areaContract;

    public function __construct(AreaContract $areaContract)
    {
        $this->areaContract = $areaContract;
    }

    public function __invoke(AreaStoreRequest $request): Area
    {
        return $this->areaContract->store(
            new Area(
                new AreaId(null),
                new AreaName($request->input('name')),
                new AreaDescription($request->input('description'))
            )
        );
    }
}
