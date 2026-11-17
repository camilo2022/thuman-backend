<?php

declare(strict_types=1);

namespace App\Domain\Contract;

use App\Domain\Contract\Values\ContractApplicationId;
use App\Domain\Contract\Values\ContractContractTypeId;
use App\Domain\Contract\Values\ContractCreatedAt;
use App\Domain\Contract\Values\ContractDeletedAt;
use App\Domain\Contract\Values\ContractEndDate;
use App\Domain\Contract\Values\ContractFileSubtypesId;
use App\Domain\Contract\Values\ContractHeadquarterId;
use App\Domain\Contract\Values\ContractId;
use App\Domain\Contract\Values\ContractLegalRepresentativeSignedAt;
use App\Domain\Contract\Values\ContractLegalRepresentativeStatus;
use App\Domain\Contract\Values\ContractPersonId;
use App\Domain\Contract\Values\ContractPositionId;
use App\Domain\Contract\Values\ContractPaymentPeriodId;
use App\Domain\Contract\Values\ContractPersonObservation;
use App\Domain\Contract\Values\ContractPersonSignedAt;
use App\Domain\Contract\Values\ContractPersonStatus;
use App\Domain\Contract\Values\ContractRelationships;
use App\Domain\Contract\Values\ContractSalary;
use App\Domain\Contract\Values\ContractSettings;
use App\Domain\Contract\Values\ContractStartDate;
use App\Domain\Contract\Values\ContractTransportAllowance;
use App\Domain\Contract\Values\ContractUpdatedAt;
use App\Domain\Contract\Values\ContractUuid;

final class Contract
{
    private $id;
    private $uuid;
    private $applicationId;
    private $personId;
    private $headquarterId;
    private $contractTypeId;
    private $positionId;
    private $salary;
    private $transportAllowance;
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
        ?ContractId $id,
        ?ContractUuid $uuid,
        ContractApplicationId $applicationId,
        ContractPersonId $personId,
        ContractHeadquarterId $headquarterId,
        ContractContractTypeId $contractTypeId,
        ContractPositionId $positionId,
        ContractSalary $salary,
        ContractTransportAllowance $transportAllowance,
        ContractPaymentPeriodId $paymentPeriodId,
        ContractStartDate $startDate,
        ContractEndDate $endDate,
        ContractPersonObservation $personObservation,
        ContractPersonSignedAt $personSignedAt,
        ContractPersonStatus $personStatus,
        ContractLegalRepresentativeSignedAt $legalRepresentativeSignedAt,
        ContractLegalRepresentativeStatus $legalRepresentativeStatus,
        ContractSettings $settings,
        ?ContractFileSubtypesId $fileSubtypesId = null,
        ?ContractCreatedAt $createdAt = null,
        ?ContractUpdatedAt $updatedAt = null,
        ?ContractDeletedAt $deletedAt = null,
        ?ContractRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->applicationId = $applicationId;
        $this->personId = $personId;
        $this->headquarterId = $headquarterId;
        $this->contractTypeId = $contractTypeId;
        $this->positionId = $positionId;
        $this->salary = $salary;
        $this->transportAllowance = $transportAllowance;
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

    public function id(): ?ContractId
    {
        return $this->id;
    }

    public function uuid(): ?ContractUuid
    {
        return $this->uuid;
    }

    public function applicationId(): ContractApplicationId
    {
        return $this->applicationId;
    }

    public function personId(): ContractPersonId
    {
        return $this->personId;
    }

    public function headquarterId(): ContractHeadquarterId
    {
        return $this->headquarterId;
    }

    public function contractTypeId(): ContractContractTypeId
    {
        return $this->contractTypeId;
    }

    public function positionId(): ContractPositionId
    {
        return $this->positionId;
    }

    public function salary(): ContractSalary
    {
        return $this->salary;
    }

    public function transportAllowance(): ContractTransportAllowance
    {
        return $this->transportAllowance;
    }

    public function paymentPeriodId(): ContractPaymentPeriodId
    {
        return $this->paymentPeriodId;
    }

    public function startDate(): ContractStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ContractEndDate
    {
        return $this->endDate;
    }

    public function personObservation(): ContractPersonObservation
    {
        return $this->personObservation;
    }

    public function personSignedAt(): ContractPersonSignedAt
    {
        return $this->personSignedAt;
    }

    public function personStatus(): ContractPersonStatus
    {
        return $this->personStatus;
    }

    public function legalRepresentativeSignedAt(): ContractLegalRepresentativeSignedAt
    {
        return $this->legalRepresentativeSignedAt;
    }

    public function legalRepresentativeStatus(): ContractLegalRepresentativeStatus
    {
        return $this->legalRepresentativeStatus;
    }

    public function settings(): ContractSettings
    {
        return $this->settings;
    }

    public function fileSubtypesId(): ?ContractFileSubtypesId
    {
        return $this->fileSubtypesId;
    }

    public function createdAt(): ?ContractCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ContractUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ContractDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?ContractRelationships
    {
        return $this->relationships;
    }
}
