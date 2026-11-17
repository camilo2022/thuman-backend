<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical;

use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalHealthEntityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalApplicationId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalCreatedAt;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDateTime;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalIndication;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalCityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalRelationships;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalResult;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalAddress;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalNeighborhood;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDescription;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalEpidemiologicalProgramId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalStatus;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalUpdatedAt;

final class ExamMedical
{
    private $id;
    private $applicationId;
    private $dateTime;
    private $cityId;
    private $healthEntityId;
    private $address;
    private $neighborhood;
    private $result;
    private $epidemiologicalProgramId;
    private $description;
    private $indication;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?ExamMedicalId $id,
        ExamMedicalApplicationId $applicationId,
        ExamMedicalDateTime $dateTime,
        ExamMedicalCityId $cityId,
        ExamMedicalHealthEntityId $healthEntityId,
        ExamMedicalAddress $address,
        ExamMedicalNeighborhood $neighborhood,
        ExamMedicalResult $result,
        ExamMedicalEpidemiologicalProgramId $epidemiologicalProgramId,
        ExamMedicalDescription $description,
        ExamMedicalIndication $indication,
        ExamMedicalStatus $status,
        ?ExamMedicalCreatedAt $createdAt = null,
        ?ExamMedicalUpdatedAt $updatedAt = null,
        ?ExamMedicalRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->dateTime = $dateTime;
        $this->cityId = $cityId;
        $this->healthEntityId = $healthEntityId;
        $this->address = $address;
        $this->neighborhood = $neighborhood;
        $this->result = $result;
        $this->description = $description;
        $this->indication = $indication;
        $this->status = $status;
        $this->epidemiologicalProgramId = $epidemiologicalProgramId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ExamMedicalId
    {
        return $this->id;
    }

    public function applicationId(): ExamMedicalApplicationId
    {
        return $this->applicationId;
    }

    public function dateTime(): ExamMedicalDateTime
    {
        return $this->dateTime;
    }

    public function cityId(): ExamMedicalCityId
    {
        return $this->cityId;
    }

    public function healthEntityId(): ExamMedicalHealthEntityId
    {
        return $this->healthEntityId;
    }

    public function address(): ExamMedicalAddress
    {
        return $this->address;
    }

    public function neighborhood(): ExamMedicalNeighborhood
    {
        return $this->neighborhood;
    }

    public function result(): ExamMedicalResult
    {
        return $this->result;
    }

    public function epidemiologicalProgramId(): ExamMedicalEpidemiologicalProgramId
    {
        return $this->epidemiologicalProgramId;
    }

    public function description(): ExamMedicalDescription
    {
        return $this->description;
    }

    public function indication(): ExamMedicalIndication
    {
        return $this->indication;
    }

    public function status(): ExamMedicalStatus
    {
        return $this->status;
    }

    public function createdAt(): ?ExamMedicalCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ExamMedicalUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?ExamMedicalRelationships
    {
        return $this->relationships;
    }
}
