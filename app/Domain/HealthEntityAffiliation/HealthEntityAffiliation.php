<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityAffiliation;

use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationCountryId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationCreatedAt;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationHealthEntityId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationObservation;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationPersonId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationRelationships;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationStatus;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationUpdatedAt;

final class HealthEntityAffiliation
{
    private $id;
    private $personId;
    private $countryId;
    private $healthEntityId;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?HealthEntityAffiliationId $id,
        HealthEntityAffiliationPersonId $personId,
        HealthEntityAffiliationCountryId $countryId,
        HealthEntityAffiliationHealthEntityId $healthEntityId,
        HealthEntityAffiliationObservation $observation,
        HealthEntityAffiliationStatus $status,
        ?HealthEntityAffiliationCreatedAt $createdAt = null,
        ?HealthEntityAffiliationUpdatedAt $updatedAt = null,
        ?HealthEntityAffiliationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->countryId = $countryId;
        $this->healthEntityId = $healthEntityId;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?HealthEntityAffiliationId
    {
        return $this->id;
    }

    public function personId(): HealthEntityAffiliationPersonId
    {
        return $this->personId;
    }

    public function countryId(): HealthEntityAffiliationCountryId
    {
        return $this->countryId;
    }

    public function healthEntityId(): HealthEntityAffiliationHealthEntityId
    {
        return $this->healthEntityId;
    }

    public function observation(): HealthEntityAffiliationObservation
    {
        return $this->observation;
    }

    public function status(): HealthEntityAffiliationStatus
    {
        return $this->status;
    }

    public function createdAt(): ?HealthEntityAffiliationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?HealthEntityAffiliationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?HealthEntityAffiliationRelationships
    {
        return $this->relationships;
    }
}
