<?php

declare(strict_types=1);

namespace App\Domain\Severance;

use App\Domain\Severance\Values\SeveranceCreatedAt;
use App\Domain\Severance\Values\SeveranceReason;
use App\Domain\Severance\Values\SeverancePersonId;
use App\Domain\Severance\Values\SeveranceId;
use App\Domain\Severance\Values\SeveranceSeveranceTypeId;
use App\Domain\Severance\Values\SeveranceRelationships;
use App\Domain\Severance\Values\SeveranceStatus;
use App\Domain\Severance\Values\SeveranceUpdatedAt;

final class Severance
{
    private $id;
    private $personId;
    private $severanceTypeId;
    private $reason;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?SeveranceId $id,
        SeverancePersonId $personId,
        SeveranceSeveranceTypeId $severanceTypeId,
        SeveranceReason $reason,
        ?SeveranceStatus $status = null,
        ?SeveranceCreatedAt $createdAt = null,
        ?SeveranceUpdatedAt $updatedAt = null,
        ?SeveranceRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->severanceTypeId = $severanceTypeId;
        $this->reason = $reason;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?SeveranceId
    {
        return $this->id;
    }

    public function personId(): SeverancePersonId
    {
        return $this->personId;
    }

    public function severanceTypeId(): SeveranceSeveranceTypeId
    {
        return $this->severanceTypeId;
    }

    public function reason(): SeveranceReason
    {
        return $this->reason;
    }

    public function status(): SeveranceStatus
    {
        return $this->status;
    }

    public function createdAt(): ?SeveranceCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SeveranceUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?SeveranceRelationships
    {
        return $this->relationships;
    }
}
