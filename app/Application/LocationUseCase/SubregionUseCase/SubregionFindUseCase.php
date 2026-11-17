<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\SubregionUseCase;

use App\Domain\Location\Subregion\Contracts\SubregionContract;
use App\Domain\Location\Subregion\Subregion;
use App\Domain\Location\Subregion\Values\SubregionId;
use App\Http\Requests\Location\Subregion\SubregionFindRequest;

final class SubregionFindUseCase
{
    private $regionContract;

    public function __construct(SubregionContract $regionContract)
    {
        $this->regionContract = $regionContract;
    }

    public function __invoke(SubregionFindRequest $request, SubregionId $regionId): ?Subregion
    {
        return $this->regionContract->find($request, $regionId);
    }
}
