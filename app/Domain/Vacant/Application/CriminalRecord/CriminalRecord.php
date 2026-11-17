<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\CriminalRecord;

use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordApplicationId;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordCreatedAt;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordId;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordObservation;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordRelationships;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordStatus;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordUpdatedAt;

final class CriminalRecord
{
    private $id;
    private $applicationId;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?CriminalRecordId $id,
        CriminalRecordApplicationId $applicationId,
        CriminalRecordObservation $observation,
        CriminalRecordStatus $status,
        ?CriminalRecordCreatedAt $createdAt = null,
        ?CriminalRecordUpdatedAt $updatedAt = null,
        ?CriminalRecordRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?CriminalRecordId
    {
        return $this->id;
    }

    public function applicationId(): CriminalRecordApplicationId
    {
        return $this->applicationId;
    }

    public function observation(): CriminalRecordObservation
    {
        return $this->observation;
    }

    public function status(): CriminalRecordStatus
    {
        return $this->status;
    }

    public function createdAt(): ?CriminalRecordCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CriminalRecordUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?CriminalRecordRelationships
    {
        return $this->relationships;
    }
}
