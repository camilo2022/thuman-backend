<?php

declare(strict_types=1);

namespace App\Domain\DocumentType;

use App\Domain\DocumentType\Values\DocumentTypeCreatedAt;
use App\Domain\DocumentType\Values\DocumentTypeDeletedAt;
use App\Domain\DocumentType\Values\DocumentTypeDescription;
use App\Domain\DocumentType\Values\DocumentTypeId;
use App\Domain\DocumentType\Values\DocumentTypeItemId;
use App\Domain\DocumentType\Values\DocumentTypeName;
use App\Domain\DocumentType\Values\DocumentTypePersonTypeId;
use App\Domain\DocumentType\Values\DocumentTypeRelationships;
use App\Domain\DocumentType\Values\DocumentTypeSettings;
use App\Domain\DocumentType\Values\DocumentTypeUpdatedAt;

final class DocumentType
{
    private $id;
    private $personTypeId;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?DocumentTypeId $id,
        DocumentTypePersonTypeId $personTypeId,
        DocumentTypeItemId $itemId,
        DocumentTypeName $name,
        DocumentTypeDescription $description,
        DocumentTypeSettings $settings,
        ?DocumentTypeCreatedAt $createdAt = null,
        ?DocumentTypeUpdatedAt $updatedAt = null,
        ?DocumentTypeDeletedAt $deletedAt = null,
        ?DocumentTypeRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personTypeId = $personTypeId;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?DocumentTypeId
    {
        return $this->id;
    }

    public function personTypeId(): DocumentTypePersonTypeId
    {
        return $this->personTypeId;
    }

    public function itemId(): DocumentTypeItemId
    {
        return $this->itemId;
    }

    public function name(): DocumentTypeName
    {
        return $this->name;
    }

    public function description(): DocumentTypeDescription
    {
        return $this->description;
    }

    public function settings(): DocumentTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?DocumentTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DocumentTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?DocumentTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?DocumentTypeRelationships
    {
        return $this->relationships;
    }
}
