<?php

declare(strict_types=1);

namespace App\Domain\EntityType;

use App\Domain\EntityType\Values\EntityTypeCountryId;
use App\Domain\EntityType\Values\EntityTypeCreatedAt;
use App\Domain\EntityType\Values\EntityTypeDeletedAt;
use App\Domain\EntityType\Values\EntityTypeDescription;
use App\Domain\EntityType\Values\EntityTypeId;
use App\Domain\EntityType\Values\EntityTypeItemId;
use App\Domain\EntityType\Values\EntityTypeName;
use App\Domain\EntityType\Values\EntityTypeRelationships;
use App\Domain\EntityType\Values\EntityTypeSettings;
use App\Domain\EntityType\Values\EntityTypeUpdatedAt;

final class EntityType
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $countryId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?EntityTypeId $id,
        EntityTypeItemId $itemId,
        EntityTypeName $name,
        EntityTypeDescription $description,
        EntityTypeSettings $settings,
        ?EntityTypeCountryId $countryId = null,
        ?EntityTypeCreatedAt $createdAt = null,
        ?EntityTypeUpdatedAt $updatedAt = null,
        ?EntityTypeDeletedAt $deletedAt = null,
        ?EntityTypeRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->countryId = $countryId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?EntityTypeId
    {
        return $this->id;
    }

    public function itemId(): EntityTypeItemId
    {
        return $this->itemId;
    }

    public function name(): EntityTypeName
    {
        return $this->name;
    }

    public function description(): EntityTypeDescription
    {
        return $this->description;
    }

    public function settings(): EntityTypeSettings
    {
        return $this->settings;
    }

    public function countryId(): ?EntityTypeCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?EntityTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?EntityTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?EntityTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?EntityTypeRelationships
    {
        return $this->relationships;
    }
}
