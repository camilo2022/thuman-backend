<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure;

use App\Domain\OrganizationalStructure\Values\OrganizationalStructureCreatedAt;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureDeletedAt;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureEndDate;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureEntityId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureParentPositionId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructurePositionId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureReason;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureRelationships;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureStartDate;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureStatus;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureUpdatedAt;

final class OrganizationalStructure
{
    private $id;
    private $entityId;
    private $positionId;
    private $parentPositionId;
    private $startDate;
    private $endDate;
    private $reason;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?OrganizationalStructureId $id,
        OrganizationalStructureEntityId $entityId,
        OrganizationalStructurePositionId $positionId,
        OrganizationalStructureParentPositionId $parentPositionId,
        OrganizationalStructureStartDate $startDate,
        OrganizationalStructureEndDate $endDate,
        OrganizationalStructureReason $reason,
        OrganizationalStructureStatus $status,
        ?OrganizationalStructureCreatedAt $createdAt = null,
        ?OrganizationalStructureUpdatedAt $updatedAt = null,
        ?OrganizationalStructureDeletedAt $deletedAt = null,
        ?OrganizationalStructureRelationships $relationships = null
    ) {
        $this->id = $id;
        $this->entityId = $entityId;
        $this->positionId = $positionId;
        $this->parentPositionId = $parentPositionId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->reason = $reason;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?OrganizationalStructureId
    {
        return $this->id;
    }

    public function entityId(): OrganizationalStructureEntityId
    {
        return $this->entityId;
    }

    public function positionId(): OrganizationalStructurePositionId
    {
        return $this->positionId;
    }

    public function parentPositionId(): OrganizationalStructureParentPositionId
    {
        return $this->parentPositionId;
    }

    public function startDate(): OrganizationalStructureStartDate
    {
        return $this->startDate;
    }

    public function endDate(): OrganizationalStructureEndDate
    {
        return $this->endDate;
    }

    public function reason(): OrganizationalStructureReason
    {
        return $this->reason;
    }

    public function status(): OrganizationalStructureStatus
    {
        return $this->status;
    }

    public function createdAt(): ?OrganizationalStructureCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?OrganizationalStructureUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?OrganizationalStructureDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?OrganizationalStructureRelationships
    {
        return $this->relationships;
    }
}
