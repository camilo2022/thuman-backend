<?php

declare(strict_types=1);

namespace App\Domain\AbsenceType;

use App\Domain\AbsenceType\Values\AbsenceTypeCreatedAt;
use App\Domain\AbsenceType\Values\AbsenceTypeDeletedAt;
use App\Domain\AbsenceType\Values\AbsenceTypeDescription;
use App\Domain\AbsenceType\Values\AbsenceTypeId;
use App\Domain\AbsenceType\Values\AbsenceTypeItemId;
use App\Domain\AbsenceType\Values\AbsenceTypeName;
use App\Domain\AbsenceType\Values\AbsenceTypeRelationships;
use App\Domain\AbsenceType\Values\AbsenceTypeSettings;
use App\Domain\AbsenceType\Values\AbsenceTypeUpdatedAt;

final class AbsenceType
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
        ?AbsenceTypeId $id,
        AbsenceTypeItemId $itemId,
        AbsenceTypeName $name,
        AbsenceTypeDescription $description,
        AbsenceTypeSettings $settings,
        ?AbsenceTypeCreatedAt $createdAt = null,
        ?AbsenceTypeUpdatedAt $updatedAt = null,
        ?AbsenceTypeDeletedAt $deletedAt = null,
        ?AbsenceTypeRelationships $relationships = null
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

    public function id(): ?AbsenceTypeId
    {
        return $this->id;
    }

    public function itemId(): AbsenceTypeItemId
    {
        return $this->itemId;
    }

    public function name(): AbsenceTypeName
    {
        return $this->name;
    }

    public function description(): AbsenceTypeDescription
    {
        return $this->description;
    }

    public function settings(): AbsenceTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?AbsenceTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AbsenceTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?AbsenceTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?AbsenceTypeRelationships
    {
        return $this->relationships;
    }
}
