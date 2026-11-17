<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase;

use App\Domain\Enterprise\Entity\Ally\Contracts\AllyContract;
use App\Domain\Enterprise\Entity\Ally\Ally;
use App\Domain\Enterprise\Entity\Ally\Values\AllyCountryId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyDescription;
use App\Domain\Enterprise\Entity\Ally\Values\AllyEntityId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyName;
use App\Domain\Enterprise\Entity\Ally\Values\AllySettings;
use App\Http\Requests\Enterprise\Entity\Ally\AllyStoreRequest;

final class AllyStoreUseCase
{
    private $allyContract;

    public function __construct(AllyContract $allyContract)
    {
        $this->allyContract = $allyContract;
    }

    public function __invoke(AllyStoreRequest $request): Ally
    {
        return $this->allyContract->store(
            new Ally(
                new AllyId(null),
                new AllyEntityId($request->input('entity_id')),
                new AllyName($request->input('name')),
                new AllyCountryId($request->input('country_id')),
                new AllyDescription($request->input('description')),
                new AllySettings($request->input('settings'))
            )
        );
    }
}
