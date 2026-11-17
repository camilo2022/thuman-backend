<?php

declare(strict_types=1);

namespace App\Domain\Termination;

use App\Domain\Termination\Values\TerminationCreatedAt;
use App\Domain\Termination\Values\TerminationDate;
use App\Domain\Termination\Values\TerminationDecision;
use App\Domain\Termination\Values\TerminationPersonId;
use App\Domain\Termination\Values\TerminationId;
use App\Domain\Termination\Values\TerminationModelId;
use App\Domain\Termination\Values\TerminationModelType;
use App\Domain\Termination\Values\TerminationReason;
use App\Domain\Termination\Values\TerminationRelationships;
use App\Domain\Termination\Values\TerminationStatus;
use App\Domain\Termination\Values\TerminationUpdatedAt;

final class Termination
{
    private $id;
    private $personId;
    private $modelId;
    private $modelType;
    private $reason;
    private $decision;
    private $status;
    private $date;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?TerminationId $id,
        TerminationPersonId $personId,
        TerminationModelId $modelId,
        TerminationModelType $modelType,
        TerminationReason $reason,
        TerminationDecision $decision,
        TerminationDate $date,
        ?TerminationStatus $status = null,
        ?TerminationCreatedAt $createdAt = null,
        ?TerminationUpdatedAt $updatedAt = null,
        ?TerminationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->reason = $reason;
        $this->decision = $decision;
        $this->status = $status;
        $this->date = $date;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?TerminationId
    {
        return $this->id;
    }

    public function personId(): TerminationPersonId
    {
        return $this->personId;
    }

    public function modelId(): TerminationModelId
    {
        return $this->modelId;
    }

    public function modelType(): TerminationModelType
    {
        return $this->modelType;
    }

    public function reason(): TerminationReason
    {
        return $this->reason;
    }

    public function decision(): TerminationDecision
    {
        return $this->decision;
    }

    public function status(): TerminationStatus
    {
        return $this->status;
    }

    public function date(): TerminationDate
    {
        return $this->date;
    }

    public function createdAt(): ?TerminationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?TerminationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?TerminationRelationships
    {
        return $this->relationships;
    }
}
