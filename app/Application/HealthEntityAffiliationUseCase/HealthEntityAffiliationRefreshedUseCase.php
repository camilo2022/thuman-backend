<?php

declare(strict_types=1);

namespace App\Application\HealthEntityAffiliationUseCase;

use App\Domain\HealthEntityAffiliation\Contracts\HealthEntityAffiliationContract;
use App\Domain\HealthEntityAffiliation\HealthEntityAffiliation;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationCountryId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationHealthEntityId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationObservation;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationPersonId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationStatus;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationRefreshedRequest;
use App\Models\HealthEntityAffiliation as HealthEntityAffiliationModel;

final class HealthEntityAffiliationRefreshedUseCase
{
    private $healthEntityAffiliationContract;

    public function __construct(HealthEntityAffiliationContract $healthEntityAffiliationContract)
    {
        $this->healthEntityAffiliationContract = $healthEntityAffiliationContract;
    }

    public function __invoke(HealthEntityAffiliationRefreshedRequest $request, HealthEntityAffiliationId $healthEntityAffiliationId): HealthEntityAffiliation
    {
        return $this->healthEntityAffiliationContract->update(
            new HealthEntityAffiliation(
                new HealthEntityAffiliationId($healthEntityAffiliationId->value()),
                new HealthEntityAffiliationPersonId($request->integer('person_id')),
                new HealthEntityAffiliationCountryId($request->integer('country_id')),
                new HealthEntityAffiliationHealthEntityId($request->integer('health_entity_id')),
                new HealthEntityAffiliationObservation($request->input('observation')),
                new HealthEntityAffiliationStatus(HealthEntityAffiliationModel::REFRESHED)
            ),
            $healthEntityAffiliationId
        );
    }
}
