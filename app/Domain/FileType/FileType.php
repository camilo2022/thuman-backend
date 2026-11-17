<?php

declare(strict_types=1);

namespace App\Domain\FileType;

use App\Domain\FileType\Values\FileTypeCreatedAt;
use App\Domain\FileType\Values\FileTypeDeletedAt;
use App\Domain\FileType\Values\FileTypeDescription;
use App\Domain\FileType\Values\FileTypeId;
use App\Domain\FileType\Values\FileTypeItemId;
use App\Domain\FileType\Values\FileTypeName;
use App\Domain\FileType\Values\FileTypeRelationships;
use App\Domain\FileType\Values\FileTypeSettings;
use App\Domain\FileType\Values\FileTypeUpdatedAt;

final class FileType
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
        ?FileTypeId $id,
        FileTypeItemId $itemId,
        FileTypeName $name,
        FileTypeDescription $description,
        FileTypeSettings $settings,
        ?FileTypeCreatedAt $createdAt = null,
        ?FileTypeUpdatedAt $updatedAt = null,
        ?FileTypeDeletedAt $deletedAt = null,
        ?FileTypeRelationships $relationships = null
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

    public function id(): ?FileTypeId
    {
        return $this->id;
    }

    public function itemId(): FileTypeItemId
    {
        return $this->itemId;
    }

    public function name(): FileTypeName
    {
        return $this->name;
    }

    public function description(): FileTypeDescription
    {
        return $this->description;
    }

    public function settings(): FileTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?FileTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FileTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?FileTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?FileTypeRelationships
    {
        return $this->relationships;
    }
}
