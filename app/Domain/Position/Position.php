<?php

declare(strict_types=1);

namespace App\Domain\Position;

use App\Domain\Position\Values\PositionAreaId;
use App\Domain\Position\Values\PositionCreatedAt;
use App\Domain\Position\Values\PositionDeletedAt;
use App\Domain\Position\Values\PositionId;
use App\Domain\Position\Values\PositionDescription;
use App\Domain\Position\Values\PositionName;
use App\Domain\Position\Values\PositionPermissionId;
use App\Domain\Position\Values\PositionProfileCategoryId;
use App\Domain\Position\Values\PositionRelationships;
use App\Domain\Position\Values\PositionSettings;
use App\Domain\Position\Values\PositionUpdatedAt;

final class Position
{
    private $id;
    private $areaId;
    private $name;
    private $profileCategoryId;
    private $description;
    private $settings;
    private $permissionId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?PositionId $id,
        PositionAreaId $areaId,
        PositionName $name,
        PositionProfileCategoryId $profileCategoryId,
        PositionDescription $description,
        PositionSettings $settings,
        ?PositionPermissionId $permissionId = null,
        ?PositionCreatedAt $createdAt = null,
        ?PositionUpdatedAt $updatedAt = null,
        ?PositionDeletedAt $deletedAt = null,
        ?PositionRelationships $relationships = null,
    )
    {
        $this->id = $id;
        $this->areaId = $areaId;
        $this->name = $name;
        $this->profileCategoryId = $profileCategoryId;
        $this->description = $description;
        $this->settings = $settings;
        $this->permissionId = $permissionId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?PositionId
    {
        return $this->id;
    }

    public function areaId(): PositionAreaId
    {
        return $this->areaId;
    }

    public function name(): PositionName
    {
        return $this->name;
    }

    public function profileCategoryId(): PositionProfileCategoryId
    {
        return $this->profileCategoryId;
    }

    public function description(): PositionDescription
    {
        return $this->description;
    }

    public function settings(): PositionSettings
    {
        return $this->settings;
    }

    public function permissionId(): ?PositionPermissionId
    {
        return $this->permissionId;
    }

    public function createdAt(): ?PositionCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PositionUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?PositionDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?PositionRelationships
    {
        return $this->relationships;
    }
}
