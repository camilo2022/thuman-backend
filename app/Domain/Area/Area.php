<?php

declare(strict_types=1);

namespace App\Domain\Area;

use App\Domain\Area\Values\AreaCreatedAt;
use App\Domain\Area\Values\AreaDeletedAt;
use App\Domain\Area\Values\AreaDescription;
use App\Domain\Area\Values\AreaId;
use App\Domain\Area\Values\AreaName;
use App\Domain\Area\Values\AreaUpdatedAt;

final class Area
{
    private $id;
    private $name;
    private $description;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?AreaId $id,
        AreaName $name,
        AreaDescription $description,
        ?AreaCreatedAt $createdAt = null,
        ?AreaUpdatedAt $updatedAt = null,
        ?AreaDeletedAt $deletedAt = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function id(): ?AreaId
    {
        return $this->id;
    }

    public function name(): AreaName
    {
        return $this->name;
    }

    public function description(): AreaDescription
    {
        return $this->description;
    }

    public function createdAt(): ?AreaCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AreaUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?AreaDeletedAt
    {
        return $this->deletedAt;
    }
}
