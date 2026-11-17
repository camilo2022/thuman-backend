<?php

declare(strict_types=1);

namespace App\Domain\AddendumType;

use App\Domain\AddendumType\Values\AddendumTypeCreatedAt;
use App\Domain\AddendumType\Values\AddendumTypeDeletedAt;
use App\Domain\AddendumType\Values\AddendumTypeDescription;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Domain\AddendumType\Values\AddendumTypeItemId;
use App\Domain\AddendumType\Values\AddendumTypeName;
use App\Domain\AddendumType\Values\AddendumTypeRelationships;
use App\Domain\AddendumType\Values\AddendumTypeSettings;
use App\Domain\AddendumType\Values\AddendumTypeUpdatedAt;

final class AddendumType
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
        ?AddendumTypeId $id,
        AddendumTypeItemId $itemId,
        AddendumTypeName $name,
        AddendumTypeDescription $description,
        AddendumTypeSettings $settings,
        ?AddendumTypeCreatedAt $createdAt = null,
        ?AddendumTypeUpdatedAt $updatedAt = null,
        ?AddendumTypeDeletedAt $deletedAt = null,
        ?AddendumTypeRelationships $relationships = null
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

    public function id(): ?AddendumTypeId
    {
        return $this->id;
    }

    public function itemId(): AddendumTypeItemId
    {
        return $this->itemId;
    }

    public function name(): AddendumTypeName
    {
        return $this->name;
    }

    public function description(): AddendumTypeDescription
    {
        return $this->description;
    }

    public function settings(): AddendumTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?AddendumTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AddendumTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?AddendumTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?AddendumTypeRelationships
    {
        return $this->relationships;
    }
}
