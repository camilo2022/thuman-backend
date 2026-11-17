<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum;

use App\Domain\Contract\Addendum\Values\AddendumAddendumTypeId;
use App\Domain\Contract\Addendum\Values\AddendumContractId;
use App\Domain\Contract\Addendum\Values\AddendumContractTypeId;
use App\Domain\Contract\Addendum\Values\AddendumCreatedAt;
use App\Domain\Contract\Addendum\Values\AddendumDeletedAt;
use App\Domain\Contract\Addendum\Values\AddendumEndDate;
use App\Domain\Contract\Addendum\Values\AddendumFileSubtypesId;
use App\Domain\Contract\Addendum\Values\AddendumHeadquarterId;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeStatus;
use App\Domain\Contract\Addendum\Values\AddendumPositionId;
use App\Domain\Contract\Addendum\Values\AddendumPaymentPeriodId;
use App\Domain\Contract\Addendum\Values\AddendumPersonObservation;
use App\Domain\Contract\Addendum\Values\AddendumPersonSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumPersonStatus;
use App\Domain\Contract\Addendum\Values\AddendumRelationships;
use App\Domain\Contract\Addendum\Values\AddendumSalary;
use App\Domain\Contract\Addendum\Values\AddendumSettings;
use App\Domain\Contract\Addendum\Values\AddendumStartDate;
use App\Domain\Contract\Addendum\Values\AddendumUpdatedAt;
use App\Domain\Contract\Addendum\Values\AddendumUuid;

final class Addendum
{
    private $id;
    private $uuid;
    private $contractId;
    private $addendumTypeId;
    private $headquarterId;
    private $contractTypeId;
    private $positionId;
    private $salary;
    private $paymentPeriodId;
    private $startDate;
    private $endDate;
    private $personObservation;
    private $personSignedAt;
    private $personStatus;
    private $legalRepresentativeSignedAt;
    private $legalRepresentativeStatus;
    private $settings;
    private $fileSubtypesId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?AddendumId $id,
        ?AddendumUuid $uuid,
        AddendumContractId $contractId,
        AddendumAddendumTypeId $addendumTypeId,
        AddendumHeadquarterId $headquarterId,
        AddendumContractTypeId $contractTypeId,
        AddendumPositionId $positionId,
        AddendumSalary $salary,
        AddendumPaymentPeriodId $paymentPeriodId,
        AddendumStartDate $startDate,
        AddendumEndDate $endDate,
        AddendumPersonObservation $personObservation,
        AddendumPersonSignedAt $personSignedAt,
        AddendumPersonStatus $personStatus,
        AddendumLegalRepresentativeSignedAt $legalRepresentativeSignedAt,
        AddendumLegalRepresentativeStatus $legalRepresentativeStatus,
        AddendumSettings $settings,
        ?AddendumFileSubtypesId $fileSubtypesId = null,
        ?AddendumCreatedAt $createdAt = null,
        ?AddendumUpdatedAt $updatedAt = null,
        ?AddendumDeletedAt $deletedAt = null,
        ?AddendumRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->contractId = $contractId;
        $this->addendumTypeId = $addendumTypeId;
        $this->headquarterId = $headquarterId;
        $this->contractTypeId = $contractTypeId;
        $this->positionId = $positionId;
        $this->salary = $salary;
        $this->paymentPeriodId = $paymentPeriodId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->personObservation = $personObservation;
        $this->personSignedAt = $personSignedAt;
        $this->personStatus = $personStatus;
        $this->legalRepresentativeSignedAt = $legalRepresentativeSignedAt;
        $this->legalRepresentativeStatus = $legalRepresentativeStatus;
        $this->settings = $settings;
        $this->fileSubtypesId = $fileSubtypesId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?AddendumId
    {
        return $this->id;
    }

    public function uuid(): ?AddendumUuid
    {
        return $this->uuid;
    }

    public function contractId(): AddendumContractId
    {
        return $this->contractId;
    }

    public function addendumTypeId(): AddendumAddendumTypeId
    {
        return $this->addendumTypeId;
    }

    public function headquarterId(): AddendumHeadquarterId
    {
        return $this->headquarterId;
    }

    public function contractTypeId(): AddendumContractTypeId
    {
        return $this->contractTypeId;
    }

    public function positionId(): AddendumPositionId
    {
        return $this->positionId;
    }

    public function salary(): AddendumSalary
    {
        return $this->salary;
    }

    public function paymentPeriodId(): AddendumPaymentPeriodId
    {
        return $this->paymentPeriodId;
    }

    public function startDate(): AddendumStartDate
    {
        return $this->startDate;
    }

    public function endDate(): AddendumEndDate
    {
        return $this->endDate;
    }

    public function personObservation(): AddendumPersonObservation
    {
        return $this->personObservation;
    }

    public function personSignedAt(): AddendumPersonSignedAt
    {
        return $this->personSignedAt;
    }

    public function personStatus(): AddendumPersonStatus
    {
        return $this->personStatus;
    }

    public function legalRepresentativeSignedAt(): AddendumLegalRepresentativeSignedAt
    {
        return $this->legalRepresentativeSignedAt;
    }

    public function legalRepresentativeStatus(): AddendumLegalRepresentativeStatus
    {
        return $this->legalRepresentativeStatus;
    }

    public function settings(): AddendumSettings
    {
        return $this->settings;
    }

    public function fileSubtypesId(): ?AddendumFileSubtypesId
    {
        return $this->fileSubtypesId;
    }

    public function createdAt(): ?AddendumCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AddendumUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?AddendumDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?AddendumRelationships
    {
        return $this->relationships;
    }
}
