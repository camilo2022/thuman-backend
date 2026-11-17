<?php

declare(strict_types=1);

namespace App\Domain\RiskManagerAffiliation;

use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationCountryId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationCreatedAt;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationRiskManagerId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationObservation;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationPersonId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationRelationships;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationStatus;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationUpdatedAt;

final class RiskManagerAffiliation
{
    private $id;
    private $personId;
    private $countryId;
    private $riskManagerId;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?RiskManagerAffiliationId $id,
        RiskManagerAffiliationPersonId $personId,
        RiskManagerAffiliationCountryId $countryId,
        RiskManagerAffiliationRiskManagerId $riskManagerId,
        RiskManagerAffiliationObservation $observation,
        RiskManagerAffiliationStatus $status,
        ?RiskManagerAffiliationCreatedAt $createdAt = null,
        ?RiskManagerAffiliationUpdatedAt $updatedAt = null,
        ?RiskManagerAffiliationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->countryId = $countryId;
        $this->riskManagerId = $riskManagerId;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?RiskManagerAffiliationId
    {
        return $this->id;
    }

    public function personId(): RiskManagerAffiliationPersonId
    {
        return $this->personId;
    }

    public function countryId(): RiskManagerAffiliationCountryId
    {
        return $this->countryId;
    }

    public function riskManagerId(): RiskManagerAffiliationRiskManagerId
    {
        return $this->riskManagerId;
    }

    public function observation(): RiskManagerAffiliationObservation
    {
        return $this->observation;
    }

    public function status(): RiskManagerAffiliationStatus
    {
        return $this->status;
    }

    public function createdAt(): ?RiskManagerAffiliationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?RiskManagerAffiliationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?RiskManagerAffiliationRelationships
    {
        return $this->relationships;
    }
}
