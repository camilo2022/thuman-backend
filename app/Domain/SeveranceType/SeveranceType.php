<?php

declare(strict_types=1);

namespace App\Domain\SeveranceType;

use App\Domain\SeveranceType\Values\SeveranceTypeCreatedAt;
use App\Domain\SeveranceType\Values\SeveranceTypeDeletedAt;
use App\Domain\SeveranceType\Values\SeveranceTypeDescription;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Domain\SeveranceType\Values\SeveranceTypeItemId;
use App\Domain\SeveranceType\Values\SeveranceTypeName;
use App\Domain\SeveranceType\Values\SeveranceTypeRelationships;
use App\Domain\SeveranceType\Values\SeveranceTypeSettings;
use App\Domain\SeveranceType\Values\SeveranceTypeUpdatedAt;

final class SeveranceType
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?SeveranceTypeId $id,
        SeveranceTypeItemId $itemId,
        SeveranceTypeName $name,
        SeveranceTypeDescription $description,
        SeveranceTypeSettings $settings,
        ?SeveranceTypeCreatedAt $createdAt = null,
        ?SeveranceTypeUpdatedAt $updatedAt = null,
        ?SeveranceTypeDeletedAt $deletedAt = null,
        ?SeveranceTypeRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?SeveranceTypeId
    {
        return $this->id;
    }

    public function itemId(): SeveranceTypeItemId
    {
        return $this->itemId;
    }

    public function name(): SeveranceTypeName
    {
        return $this->name;
    }

    public function description(): SeveranceTypeDescription
    {
        return $this->description;
    }

    public function settings(): SeveranceTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?SeveranceTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SeveranceTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?SeveranceTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?SeveranceTypeRelationships
    {
        return $this->relationships;
    }
}
