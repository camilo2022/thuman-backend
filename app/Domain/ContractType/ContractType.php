<?php

declare(strict_types=1);

namespace App\Domain\ContractType;

use App\Domain\ContractType\Values\ContractTypeCreatedAt;
use App\Domain\ContractType\Values\ContractTypeDeletedAt;
use App\Domain\ContractType\Values\ContractTypeDescription;
use App\Domain\ContractType\Values\ContractTypeFileSubtypesId;
use App\Domain\ContractType\Values\ContractTypeId;
use App\Domain\ContractType\Values\ContractTypeItemId;
use App\Domain\ContractType\Values\ContractTypeName;
use App\Domain\ContractType\Values\ContractTypeRelationships;
use App\Domain\ContractType\Values\ContractTypeSettings;
use App\Domain\ContractType\Values\ContractTypeUpdatedAt;

final class ContractType
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $fileSubtypesId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?ContractTypeId $id,
        ContractTypeItemId $itemId,
        ContractTypeName $name,
        ContractTypeDescription $description,
        ContractTypeSettings $settings,
        ?ContractTypeFileSubtypesId $fileSubtypesId = null,
        ?ContractTypeCreatedAt $createdAt = null,
        ?ContractTypeUpdatedAt $updatedAt = null,
        ?ContractTypeDeletedAt $deletedAt = null,
        ?ContractTypeRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->fileSubtypesId = $fileSubtypesId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ContractTypeId
    {
        return $this->id;
    }

    public function itemId(): ContractTypeItemId
    {
        return $this->itemId;
    }

    public function name(): ContractTypeName
    {
        return $this->name;
    }

    public function description(): ContractTypeDescription
    {
        return $this->description;
    }

    public function settings(): ContractTypeSettings
    {
        return $this->settings;
    }

    public function fileSubtypesId(): ?ContractTypeFileSubtypesId
    {
        return $this->fileSubtypesId;
    }

    public function createdAt(): ?ContractTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ContractTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ContractTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?ContractTypeRelationships
    {
        return $this->relationships;
    }
}
