<?php

declare(strict_types=1);

namespace App\Domain\ResignationType;

use App\Domain\ResignationType\Values\ResignationTypeCreatedAt;
use App\Domain\ResignationType\Values\ResignationTypeDeletedAt;
use App\Domain\ResignationType\Values\ResignationTypeDescription;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Domain\ResignationType\Values\ResignationTypeItemId;
use App\Domain\ResignationType\Values\ResignationTypeName;
use App\Domain\ResignationType\Values\ResignationTypeRelationships;
use App\Domain\ResignationType\Values\ResignationTypeSettings;
use App\Domain\ResignationType\Values\ResignationTypeUpdatedAt;

final class ResignationType
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
        ?ResignationTypeId $id,
        ResignationTypeItemId $itemId,
        ResignationTypeName $name,
        ResignationTypeDescription $description,
        ResignationTypeSettings $settings,
        ?ResignationTypeCreatedAt $createdAt = null,
        ?ResignationTypeUpdatedAt $updatedAt = null,
        ?ResignationTypeDeletedAt $deletedAt = null,
        ?ResignationTypeRelationships $relationships = null
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

    public function id(): ?ResignationTypeId
    {
        return $this->id;
    }

    public function itemId(): ResignationTypeItemId
    {
        return $this->itemId;
    }

    public function name(): ResignationTypeName
    {
        return $this->name;
    }

    public function description(): ResignationTypeDescription
    {
        return $this->description;
    }

    public function settings(): ResignationTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?ResignationTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ResignationTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ResignationTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?ResignationTypeRelationships
    {
        return $this->relationships;
    }
}
