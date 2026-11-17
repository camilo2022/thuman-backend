<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\HeadquarterUseCase;

use App\Domain\Enterprise\Entity\Headquarter\Contracts\HeadquarterContract;
use App\Domain\Enterprise\Entity\Headquarter\Headquarter;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterAddress;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterCityId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterEntityId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterName;
use App\Http\Requests\Enterprise\Entity\Headquarter\HeadquarterStoreRequest;

final class HeadquarterStoreUseCase
{
    private $headquarterContract;

    public function __construct(HeadquarterContract $headquarterContract)
    {
        $this->headquarterContract = $headquarterContract;
    }

    public function __invoke(HeadquarterStoreRequest $request): Headquarter
    {
        return $this->headquarterContract->store(
            new Headquarter(
                new HeadquarterId(null),
                new HeadquarterEntityId($request->input('entity_id')),
                new HeadquarterName($request->input('name')),
                new HeadquarterCityId($request->input('city_id')),
                new HeadquarterAddress($request->input('address')),
            )
        );
    }
}
