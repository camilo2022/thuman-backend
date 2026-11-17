<?php

declare(strict_types=1);

namespace App\Domain\PersonType;

use App\Domain\PersonType\Values\PersonTypeCreatedAt;
use App\Domain\PersonType\Values\PersonTypeDeletedAt;
use App\Domain\PersonType\Values\PersonTypeDescription;
use App\Domain\PersonType\Values\PersonTypeId;
use App\Domain\PersonType\Values\PersonTypeItemId;
use App\Domain\PersonType\Values\PersonTypeName;
use App\Domain\PersonType\Values\PersonTypeRelationships;
use App\Domain\PersonType\Values\PersonTypeSettings;
use App\Domain\PersonType\Values\PersonTypeUpdatedAt;

final class PersonType
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
        ?PersonTypeId $id,
        PersonTypeItemId $itemId,
        PersonTypeName $name,
        PersonTypeDescription $description,
        PersonTypeSettings $settings,
        ?PersonTypeCreatedAt $createdAt = null,
        ?PersonTypeUpdatedAt $updatedAt = null,
        ?PersonTypeDeletedAt $deletedAt = null,
        ?PersonTypeRelationships $relationships = null
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

    public function id(): ?PersonTypeId
    {
        return $this->id;
    }

    public function itemId(): PersonTypeItemId
    {
        return $this->itemId;
    }

    public function name(): PersonTypeName
    {
        return $this->name;
    }

    public function description(): PersonTypeDescription
    {
        return $this->description;
    }

    public function settings(): PersonTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?PersonTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PersonTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?PersonTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?PersonTypeRelationships
    {
        return $this->relationships;
    }
}
