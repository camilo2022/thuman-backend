<?php

declare(strict_types=1);

namespace App\Application\RiskManagerAffiliationUseCase;

use App\Domain\RiskManagerAffiliation\Contracts\RiskManagerAffiliationContract;
use App\Domain\RiskManagerAffiliation\RiskManagerAffiliation;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationCountryId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationRiskManagerId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationObservation;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationPersonId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationStatus;
use App\Http\Requests\RiskManagerAffiliation\RiskManagerAffiliationApprovedRequest;
use App\Models\RiskManagerAffiliation as RiskManagerAffiliationModel;

final class RiskManagerAffiliationApprovedUseCase
{
    private $riskManagerAffiliationContract;

    public function __construct(RiskManagerAffiliationContract $riskManagerAffiliationContract)
    {
        $this->riskManagerAffiliationContract = $riskManagerAffiliationContract;
    }

    public function __invoke(RiskManagerAffiliationApprovedRequest $request, RiskManagerAffiliationId $riskManagerAffiliationId): RiskManagerAffiliation
    {
        return $this->riskManagerAffiliationContract->update(
            new RiskManagerAffiliation(
                new RiskManagerAffiliationId($riskManagerAffiliationId->value()),
                new RiskManagerAffiliationPersonId($request->integer('person_id')),
                new RiskManagerAffiliationCountryId($request->integer('country_id')),
                new RiskManagerAffiliationRiskManagerId($request->integer('risk_manager_id')),
                new RiskManagerAffiliationObservation($request->input('observation')),
                new RiskManagerAffiliationStatus(RiskManagerAffiliationModel::APPROVED)
            ),
            $riskManagerAffiliationId
        );
    }
}
