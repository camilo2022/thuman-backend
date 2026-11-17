<?php

declare(strict_types=1);

namespace App\Domain\Vacant;

use App\Domain\Vacant\Values\VacantBenefitsId;
use App\Domain\Vacant\Values\VacantContractTypeId;
use App\Domain\Vacant\Values\VacantCreatedAt;
use App\Domain\Vacant\Values\VacantDescription;
use App\Domain\Vacant\Values\VacantEndDate;
use App\Domain\Vacant\Values\VacantHeadquarterId;
use App\Domain\Vacant\Values\VacantHiringReasonId;
use App\Domain\Vacant\Values\VacantId;
use App\Domain\Vacant\Values\VacantPersonId;
use App\Domain\Vacant\Values\VacantPositionId;
use App\Domain\Vacant\Values\VacantQuantity;
use App\Domain\Vacant\Values\VacantRelationships;
use App\Domain\Vacant\Values\VacantSalaryMax;
use App\Domain\Vacant\Values\VacantSalaryMin;
use App\Domain\Vacant\Values\VacantSettings;
use App\Domain\Vacant\Values\VacantStartDate;
use App\Domain\Vacant\Values\VacantStatus;
use App\Domain\Vacant\Values\VacantUpdatedAt;
use App\Domain\Vacant\Values\VacantUuid;
use App\Domain\Vacant\Values\VacantWorkArrangementId;
use App\Domain\Vacant\Values\VacantWorkDayId;

final class Vacant
{
    private $id;
    private $uuid;
    private $hiringReasonId;
    private $positionId;
    private $personId;
    private $headquarterId;
    private $contractTypeId;
    private $workArrangementId;
    private $workDayId;
    private $salaryMin;
    private $salaryMax;
    private $startDate;
    private $endDate;
    private $description;
    private $quantity;
    private $status;
    private $settings;
    private $benefitsId;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?VacantId $id,
        ?VacantUuid $uuid,
        VacantHiringReasonId $hiringReasonId,
        VacantPositionId $positionId,
        VacantPersonId $personId,
        VacantHeadquarterId $headquarterId,
        VacantContractTypeId $contractTypeId,
        VacantWorkArrangementId $workArrangementId,
        VacantWorkDayId $workDayId,
        VacantSalaryMin $salaryMin,
        ?VacantSalaryMax $salaryMax = null,
        VacantStartDate $startDate,
        ?VacantEndDate $endDate,
        VacantDescription $description,
        VacantQuantity $quantity,
        ?VacantStatus $status = null,
        VacantSettings $settings,
        ?VacantBenefitsId $benefitsId = null,
        ?VacantCreatedAt $createdAt = null,
        ?VacantUpdatedAt $updatedAt = null,
        ?VacantRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->hiringReasonId = $hiringReasonId;
        $this->positionId = $positionId;
        $this->personId = $personId;
        $this->headquarterId = $headquarterId;
        $this->contractTypeId = $contractTypeId;
        $this->workArrangementId = $workArrangementId;
        $this->workDayId = $workDayId;
        $this->salaryMin = $salaryMin;
        $this->salaryMax = $salaryMax;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->status = $status;
        $this->settings = $settings;
        $this->benefitsId = $benefitsId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?VacantId
    {
        return $this->id;
    }

    public function uuid(): ?VacantUuid
    {
        return $this->uuid;
    }

    public function hiringReasonId(): VacantHiringReasonId
    {
        return $this->hiringReasonId;
    }

    public function positionId(): VacantPositionId
    {
        return $this->positionId;
    }

    public function personId(): VacantPersonId
    {
        return $this->personId;
    }

    public function headquarterId(): VacantHeadquarterId
    {
        return $this->headquarterId;
    }

    public function contractTypeId(): VacantContractTypeId
    {
        return $this->contractTypeId;
    }

    public function workArrangementId(): VacantWorkArrangementId
    {
        return $this->workArrangementId;
    }

    public function workDayId(): VacantWorkDayId
    {
        return $this->workDayId;
    }

    public function salaryMin(): VacantSalaryMin
    {
        return $this->salaryMin;
    }

    public function salaryMax(): ?VacantSalaryMax
    {
        return $this->salaryMax;
    }

    public function startDate(): VacantStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ?VacantEndDate
    {
        return $this->endDate;
    }

    public function description(): VacantDescription
    {
        return $this->description;
    }

    public function quantity(): VacantQuantity
    {
        return $this->quantity;
    }

    public function status(): VacantStatus
    {
        return $this->status;
    }

    public function settings(): VacantSettings
    {
        return $this->settings;
    }

    public function benefitsId(): ?VacantBenefitsId
    {
        return $this->benefitsId;
    }

    public function createdAt(): ?VacantCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?VacantUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?VacantRelationships
    {
        return $this->relationships;
    }
}
