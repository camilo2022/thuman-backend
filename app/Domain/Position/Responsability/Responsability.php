<?php

declare(strict_types=1);

namespace App\Domain\Position\Responsability;

use App\Domain\Position\Responsability\Values\ResponsabilityName;
use App\Domain\Position\Responsability\Values\ResponsabilityCreatedAt;
use App\Domain\Position\Responsability\Values\ResponsabilityId;
use App\Domain\Position\Responsability\Values\ResponsabilityDescription;
use App\Domain\Position\Responsability\Values\ResponsabilityPositionId;
use App\Domain\Position\Responsability\Values\ResponsabilityUpdatedAt;

final class Responsability
{
    private $id;
    private $positionId;
    private $name;
    private $description;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        ?ResponsabilityId $id,
        ResponsabilityPositionId $positionId,
        ResponsabilityName $name,
        ResponsabilityDescription $description,
        ?ResponsabilityCreatedAt $createdAt = null,
        ?ResponsabilityUpdatedAt $updatedAt = null
    )
    {
        $this->id = $id;
        $this->positionId = $positionId;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?ResponsabilityId
    {
        return $this->id;
    }

    public function positionId(): ResponsabilityPositionId
    {
        return $this->positionId;
    }

    public function name(): ResponsabilityName
    {
        return $this->name;
    }

    public function description(): ResponsabilityDescription
    {
        return $this->description;
    }

    public function createdAt(): ?ResponsabilityCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ResponsabilityUpdatedAt
    {
        return $this->updatedAt;
    }
}
