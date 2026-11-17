<?php

declare(strict_types=1);

namespace App\Domain\Vacation;

use App\Domain\Vacation\Values\VacationCreatedAt;
use App\Domain\Vacation\Values\VacationPeriod;
use App\Domain\Vacation\Values\VacationEndDate;
use App\Domain\Vacation\Values\VacationPersonId;
use App\Domain\Vacation\Values\VacationId;
use App\Domain\Vacation\Values\VacationVacationTypeId;
use App\Domain\Vacation\Values\VacationDays;
use App\Domain\Vacation\Values\VacationRelationships;
use App\Domain\Vacation\Values\VacationStartDate;
use App\Domain\Vacation\Values\VacationStatus;
use App\Domain\Vacation\Values\VacationUpdatedAt;

final class Vacation
{
    private $id;
    private $personId;
    private $vacationTypeId;
    private $period;
    private $days;
    private $startDate;
    private $endDate;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?VacationId $id,
        VacationPersonId $personId,
        VacationVacationTypeId $vacationTypeId,
        VacationPeriod $period,
        VacationDays $days,
        VacationStartDate $startDate,
        ?VacationEndDate $endDate,
        ?VacationStatus $status = null,
        ?VacationCreatedAt $createdAt = null,
        ?VacationUpdatedAt $updatedAt = null,
        ?VacationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->vacationTypeId = $vacationTypeId;
        $this->period = $period;
        $this->days = $days;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?VacationId
    {
        return $this->id;
    }

    public function personId(): VacationPersonId
    {
        return $this->personId;
    }

    public function vacationTypeId(): VacationVacationTypeId
    {
        return $this->vacationTypeId;
    }

    public function period(): VacationPeriod
    {
        return $this->period;
    }

    public function days(): VacationDays
    {
        return $this->days;
    }

    public function startDate(): VacationStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ?VacationEndDate
    {
        return $this->endDate;
    }

    public function status(): VacationStatus
    {
        return $this->status;
    }

    public function createdAt(): ?VacationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?VacationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?VacationRelationships
    {
        return $this->relationships;
    }
}
