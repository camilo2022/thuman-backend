<?php

declare(strict_types=1);

namespace App\Domain\Disability;

use App\Domain\Disability\Values\DisabilityCIECodeId;
use App\Domain\Disability\Values\DisabilityCreatedAt;
use App\Domain\Disability\Values\DisabilityReason;
use App\Domain\Disability\Values\DisabilityEndDate;
use App\Domain\Disability\Values\DisabilityPersonId;
use App\Domain\Disability\Values\DisabilityId;
use App\Domain\Disability\Values\DisabilityDisabilityTypeId;
use App\Domain\Disability\Values\DisabilityRelationships;
use App\Domain\Disability\Values\DisabilityStartDate;
use App\Domain\Disability\Values\DisabilityStatus;
use App\Domain\Disability\Values\DisabilityUpdatedAt;

final class Disability
{
    private $id;
    private $personId;
    private $disabilityTypeId;
    private $cieCodeId;
    private $startDate;
    private $endDate;
    private $reason;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?DisabilityId $id,
        DisabilityPersonId $personId,
        DisabilityDisabilityTypeId $disabilityTypeId,
        DisabilityCIECodeId $cieCodeId,
        DisabilityStartDate $startDate,
        ?DisabilityEndDate $endDate,
        DisabilityReason $reason,
        ?DisabilityStatus $status = null,
        ?DisabilityCreatedAt $createdAt = null,
        ?DisabilityUpdatedAt $updatedAt = null,
        ?DisabilityRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->disabilityTypeId = $disabilityTypeId;
        $this->cieCodeId = $cieCodeId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->reason = $reason;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?DisabilityId
    {
        return $this->id;
    }

    public function personId(): DisabilityPersonId
    {
        return $this->personId;
    }

    public function disabilityTypeId(): DisabilityDisabilityTypeId
    {
        return $this->disabilityTypeId;
    }

    public function cieCodeId(): DisabilityCIECodeId
    {
        return $this->cieCodeId;
    }

    public function startDate(): DisabilityStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ?DisabilityEndDate
    {
        return $this->endDate;
    }

    public function reason(): DisabilityReason
    {
        return $this->reason;
    }

    public function status(): DisabilityStatus
    {
        return $this->status;
    }

    public function createdAt(): ?DisabilityCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DisabilityUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?DisabilityRelationships
    {
        return $this->relationships;
    }
}
