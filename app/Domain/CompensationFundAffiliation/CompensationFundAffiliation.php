<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation;

use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCountryId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCreatedAt;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCompensationFundId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationObservation;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationPersonId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationRelationships;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationStatus;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationUpdatedAt;

final class CompensationFundAffiliation
{
    private $id;
    private $personId;
    private $countryId;
    private $compensationFundId;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?CompensationFundAffiliationId $id,
        CompensationFundAffiliationPersonId $personId,
        CompensationFundAffiliationCountryId $countryId,
        CompensationFundAffiliationCompensationFundId $compensationFundId,
        CompensationFundAffiliationObservation $observation,
        CompensationFundAffiliationStatus $status,
        ?CompensationFundAffiliationCreatedAt $createdAt = null,
        ?CompensationFundAffiliationUpdatedAt $updatedAt = null,
        ?CompensationFundAffiliationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->countryId = $countryId;
        $this->compensationFundId = $compensationFundId;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?CompensationFundAffiliationId
    {
        return $this->id;
    }

    public function personId(): CompensationFundAffiliationPersonId
    {
        return $this->personId;
    }

    public function countryId(): CompensationFundAffiliationCountryId
    {
        return $this->countryId;
    }

    public function compensationFundId(): CompensationFundAffiliationCompensationFundId
    {
        return $this->compensationFundId;
    }

    public function observation(): CompensationFundAffiliationObservation
    {
        return $this->observation;
    }

    public function status(): CompensationFundAffiliationStatus
    {
        return $this->status;
    }

    public function createdAt(): ?CompensationFundAffiliationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CompensationFundAffiliationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?CompensationFundAffiliationRelationships
    {
        return $this->relationships;
    }
}
