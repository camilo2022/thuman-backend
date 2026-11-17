<?php

declare(strict_types=1);

namespace App\Domain\License;

use App\Domain\License\Values\LicenseCreatedAt;
use App\Domain\License\Values\LicenseReason;
use App\Domain\License\Values\LicenseEndDate;
use App\Domain\License\Values\LicensePersonId;
use App\Domain\License\Values\LicenseId;
use App\Domain\License\Values\LicenseLicenseTypeId;
use App\Domain\License\Values\LicenseRelationships;
use App\Domain\License\Values\LicenseStartDate;
use App\Domain\License\Values\LicenseStatus;
use App\Domain\License\Values\LicenseUpdatedAt;

final class License
{
    private $id;
    private $personId;
    private $licenseTypeId;
    private $startDate;
    private $endDate;
    private $reason;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?LicenseId $id,
        LicensePersonId $personId,
        LicenseLicenseTypeId $licenseTypeId,
        LicenseStartDate $startDate,
        ?LicenseEndDate $endDate,
        LicenseReason $reason,
        ?LicenseStatus $status = null,
        ?LicenseCreatedAt $createdAt = null,
        ?LicenseUpdatedAt $updatedAt = null,
        ?LicenseRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->licenseTypeId = $licenseTypeId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->reason = $reason;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?LicenseId
    {
        return $this->id;
    }

    public function personId(): LicensePersonId
    {
        return $this->personId;
    }

    public function licenseTypeId(): LicenseLicenseTypeId
    {
        return $this->licenseTypeId;
    }

    public function startDate(): LicenseStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ?LicenseEndDate
    {
        return $this->endDate;
    }

    public function reason(): LicenseReason
    {
        return $this->reason;
    }

    public function status(): LicenseStatus
    {
        return $this->status;
    }

    public function createdAt(): ?LicenseCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?LicenseUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?LicenseRelationships
    {
        return $this->relationships;
    }
}
