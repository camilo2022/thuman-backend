<?php

declare(strict_types=1);

namespace App\Domain\DisabilityType;

use App\Domain\DisabilityType\Values\DisabilityTypeCreatedAt;
use App\Domain\DisabilityType\Values\DisabilityTypeDeletedAt;
use App\Domain\DisabilityType\Values\DisabilityTypeDescription;
use App\Domain\DisabilityType\Values\DisabilityTypeId;
use App\Domain\DisabilityType\Values\DisabilityTypeItemId;
use App\Domain\DisabilityType\Values\DisabilityTypeName;
use App\Domain\DisabilityType\Values\DisabilityTypeRelationships;
use App\Domain\DisabilityType\Values\DisabilityTypeSettings;
use App\Domain\DisabilityType\Values\DisabilityTypeUpdatedAt;

final class DisabilityType
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
        ?DisabilityTypeId $id,
        DisabilityTypeItemId $itemId,
        DisabilityTypeName $name,
        DisabilityTypeDescription $description,
        DisabilityTypeSettings $settings,
        ?DisabilityTypeCreatedAt $createdAt = null,
        ?DisabilityTypeUpdatedAt $updatedAt = null,
        ?DisabilityTypeDeletedAt $deletedAt = null,
        ?DisabilityTypeRelationships $relationships = null
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

    public function id(): ?DisabilityTypeId
    {
        return $this->id;
    }

    public function itemId(): DisabilityTypeItemId
    {
        return $this->itemId;
    }

    public function name(): DisabilityTypeName
    {
        return $this->name;
    }

    public function description(): DisabilityTypeDescription
    {
        return $this->description;
    }

    public function settings(): DisabilityTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?DisabilityTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DisabilityTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?DisabilityTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?DisabilityTypeRelationships
    {
        return $this->relationships;
    }
}
