<?php

declare(strict_types=1);

namespace App\Application\HealthEntityUseCase;

use App\Domain\HealthEntity\Contracts\HealthEntityContract;
use App\Domain\HealthEntity\HealthEntity;
use App\Domain\HealthEntity\Values\HealthEntityCountryId;
use App\Domain\HealthEntity\Values\HealthEntityDescription;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Domain\HealthEntity\Values\HealthEntityItemId;
use App\Domain\HealthEntity\Values\HealthEntityName;
use App\Domain\HealthEntity\Values\HealthEntityHealthEntityTypeId;
use App\Domain\HealthEntity\Values\HealthEntitySettings;
use App\Http\Requests\HealthEntity\HealthEntityToggleRequest;

final class HealthEntityToggleUseCase
{
    private $healthEntityContract;

    public function __construct(HealthEntityContract $healthEntityContract)
    {
        $this->healthEntityContract = $healthEntityContract;
    }

    public function __invoke(HealthEntityToggleRequest $request, HealthEntityId $healthEntityId): HealthEntity
    {
        return $this->healthEntityContract->toggle(
            new HealthEntity(
                new HealthEntityId($healthEntityId->value()),
                new HealthEntityHealthEntityTypeId($request->integer('health_entity_type_id')),
                new HealthEntityItemId($request->integer('item_id')),
                new HealthEntityName($request->input('name')),
                new HealthEntityDescription($request->input('description')),
                new HealthEntitySettings((object) []),
                new HealthEntityCountryId($request->integer('country_id'))
            ),
            $healthEntityId
        );
    }
}
