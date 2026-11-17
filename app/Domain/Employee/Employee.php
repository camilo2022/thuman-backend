<?php

declare(strict_types=1);

namespace App\Domain\Employee;

use App\Domain\Employee\Values\EmployeeCampaignId;
use App\Domain\Employee\Values\EmployeeCreatedAt;
use App\Domain\Employee\Values\EmployeeDeletedAt;
use App\Domain\Employee\Values\EmployeeEndDate;
use App\Domain\Employee\Values\EmployeeHeadquarterId;
use App\Domain\Employee\Values\EmployeeId;
use App\Domain\Employee\Values\EmployeePersonId;
use App\Domain\Employee\Values\EmployeePositionId;
use App\Domain\Employee\Values\EmployeeStartDate;
use App\Domain\Employee\Values\EmployeeUpdatedAt;
use App\Domain\Employee\Values\EmployeeRelationships;
use App\Domain\Employee\Values\EmployeeSettings;

final class Employee
{
    private $id;
    private $personId;
    private $headquarterId;
    private $positionId;
    private $campaignId;
    private $startDate;
    private $endDate;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?EmployeeId $id,
        EmployeePersonId $personId,
        EmployeeHeadquarterId $headquarterId,
        EmployeePositionId $positionId,
        EmployeeCampaignId $campaignId,
        EmployeeStartDate $startDate,
        EmployeeEndDate $endDate,
        EmployeeSettings $settings,
        ?EmployeeCreatedAt $createdAt = null,
        ?EmployeeUpdatedAt $updatedAt = null,
        ?EmployeeDeletedAt $deletedAt = null,
        ?EmployeeRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->headquarterId = $headquarterId;
        $this->positionId = $positionId;
        $this->campaignId = $campaignId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?EmployeeId
    {
        return $this->id;
    }

    public function personId(): EmployeePersonId
    {
        return $this->personId;
    }

    public function headquarterId(): EmployeeHeadquarterId
    {
        return $this->headquarterId;
    }

    public function positionId(): EmployeePositionId
    {
        return $this->positionId;
    }

    public function campaignId(): EmployeeCampaignId
    {
        return $this->campaignId;
    }

    public function startDate(): EmployeeStartDate
    {
        return $this->startDate;
    }

    public function endDate(): EmployeeEndDate
    {
        return $this->endDate;
    }

    public function settings(): EmployeeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?EmployeeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?EmployeeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?EmployeeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?EmployeeRelationships
    {
        return $this->relationships;
    }
}
