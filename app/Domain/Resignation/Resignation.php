<?php

declare(strict_types=1);

namespace App\Domain\Resignation;

use App\Domain\Resignation\Values\ResignationCreatedAt;
use App\Domain\Resignation\Values\ResignationDate;
use App\Domain\Resignation\Values\ResignationPersonId;
use App\Domain\Resignation\Values\ResignationId;
use App\Domain\Resignation\Values\ResignationResignationTypeId;
use App\Domain\Resignation\Values\ResignationReason;
use App\Domain\Resignation\Values\ResignationRelationships;
use App\Domain\Resignation\Values\ResignationStatus;
use App\Domain\Resignation\Values\ResignationUpdatedAt;

final class Resignation
{
    private $id;
    private $personId;
    private $resignationTypeId;
    private $date;
    private $reason;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?ResignationId $id,
        ResignationPersonId $personId,
        ResignationResignationTypeId $resignationTypeId,
        ResignationDate $date,
        ResignationReason $reason,
        ?ResignationStatus $status = null,
        ?ResignationCreatedAt $createdAt = null,
        ?ResignationUpdatedAt $updatedAt = null,
        ?ResignationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->resignationTypeId = $resignationTypeId;
        $this->date = $date;
        $this->reason = $reason;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ResignationId
    {
        return $this->id;
    }

    public function personId(): ResignationPersonId
    {
        return $this->personId;
    }

    public function resignationTypeId(): ResignationResignationTypeId
    {
        return $this->resignationTypeId;
    }

    public function date(): ResignationDate
    {
        return $this->date;
    }

    public function reason(): ResignationReason
    {
        return $this->reason;
    }

    public function status(): ResignationStatus
    {
        return $this->status;
    }

    public function createdAt(): ?ResignationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ResignationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?ResignationRelationships
    {
        return $this->relationships;
    }
}
