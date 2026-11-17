<?php

declare(strict_types=1);

namespace App\Domain\Absence;

use App\Domain\Absence\Values\AbsenceCreatedAt;
use App\Domain\Absence\Values\AbsenceReason;
use App\Domain\Absence\Values\AbsenceEndDate;
use App\Domain\Absence\Values\AbsencePersonId;
use App\Domain\Absence\Values\AbsenceId;
use App\Domain\Absence\Values\AbsenceAbsenceTypeId;
use App\Domain\Absence\Values\AbsenceCompensation;
use App\Domain\Absence\Values\AbsenceRelationships;
use App\Domain\Absence\Values\AbsenceStartDate;
use App\Domain\Absence\Values\AbsenceStatus;
use App\Domain\Absence\Values\AbsenceUpdatedAt;

final class Absence
{
    private $id;
    private $personId;
    private $absenceTypeId;
    private $startDate;
    private $endDate;
    private $reason;
    private $compensation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?AbsenceId $id,
        AbsencePersonId $personId,
        AbsenceAbsenceTypeId $absenceTypeId,
        AbsenceStartDate $startDate,
        ?AbsenceEndDate $endDate,
        AbsenceReason $reason,
        AbsenceCompensation $compensation,
        ?AbsenceStatus $status = null,
        ?AbsenceCreatedAt $createdAt = null,
        ?AbsenceUpdatedAt $updatedAt = null,
        ?AbsenceRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->absenceTypeId = $absenceTypeId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->reason = $reason;
        $this->compensation = $compensation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?AbsenceId
    {
        return $this->id;
    }

    public function personId(): AbsencePersonId
    {
        return $this->personId;
    }

    public function absenceTypeId(): AbsenceAbsenceTypeId
    {
        return $this->absenceTypeId;
    }

    public function startDate(): AbsenceStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ?AbsenceEndDate
    {
        return $this->endDate;
    }

    public function reason(): AbsenceReason
    {
        return $this->reason;
    }

    public function compensation(): AbsenceCompensation
    {
        return $this->compensation;
    }

    public function status(): AbsenceStatus
    {
        return $this->status;
    }

    public function createdAt(): ?AbsenceCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AbsenceUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?AbsenceRelationships
    {
        return $this->relationships;
    }
}
