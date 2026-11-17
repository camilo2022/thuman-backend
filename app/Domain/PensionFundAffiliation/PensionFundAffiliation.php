<?php

declare(strict_types=1);

namespace App\Domain\PensionFundAffiliation;

use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationCountryId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationCreatedAt;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPensionFundId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationObservation;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPersonId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationRelationships;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationStatus;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationUpdatedAt;

final class PensionFundAffiliation
{
    private $id;
    private $personId;
    private $countryId;
    private $pensionFundId;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?PensionFundAffiliationId $id,
        PensionFundAffiliationPersonId $personId,
        PensionFundAffiliationCountryId $countryId,
        PensionFundAffiliationPensionFundId $pensionFundId,
        PensionFundAffiliationObservation $observation,
        PensionFundAffiliationStatus $status,
        ?PensionFundAffiliationCreatedAt $createdAt = null,
        ?PensionFundAffiliationUpdatedAt $updatedAt = null,
        ?PensionFundAffiliationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->countryId = $countryId;
        $this->pensionFundId = $pensionFundId;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?PensionFundAffiliationId
    {
        return $this->id;
    }

    public function personId(): PensionFundAffiliationPersonId
    {
        return $this->personId;
    }

    public function countryId(): PensionFundAffiliationCountryId
    {
        return $this->countryId;
    }

    public function pensionFundId(): PensionFundAffiliationPensionFundId
    {
        return $this->pensionFundId;
    }

    public function observation(): PensionFundAffiliationObservation
    {
        return $this->observation;
    }

    public function status(): PensionFundAffiliationStatus
    {
        return $this->status;
    }

    public function createdAt(): ?PensionFundAffiliationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PensionFundAffiliationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?PensionFundAffiliationRelationships
    {
        return $this->relationships;
    }
}
