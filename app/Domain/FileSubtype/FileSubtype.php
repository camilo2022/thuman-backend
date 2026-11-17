<?php

declare(strict_types=1);

namespace App\Domain\FileSubtype;

use App\Domain\FileSubtype\Values\FileSubtypeCreatedAt;
use App\Domain\FileSubtype\Values\FileSubtypeDeletedAt;
use App\Domain\FileSubtype\Values\FileSubtypeDescription;
use App\Domain\FileSubtype\Values\FileSubtypeId;
use App\Domain\FileSubtype\Values\FileSubtypeItemId;
use App\Domain\FileSubtype\Values\FileSubtypeName;
use App\Domain\FileSubtype\Values\FileSubtypeRelationships;
use App\Domain\FileSubtype\Values\FileSubtypeSettings;
use App\Domain\FileSubtype\Values\FileSubtypeUpdatedAt;

final class FileSubtype
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
        ?FileSubtypeId $id,
        FileSubtypeItemId $itemId,
        FileSubtypeName $name,
        FileSubtypeDescription $description,
        FileSubtypeSettings $settings,
        ?FileSubtypeCreatedAt $createdAt = null,
        ?FileSubtypeUpdatedAt $updatedAt = null,
        ?FileSubtypeDeletedAt $deletedAt = null,
        ?FileSubtypeRelationships $relationships = null
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

    public function id(): ?FileSubtypeId
    {
        return $this->id;
    }

    public function itemId(): FileSubtypeItemId
    {
        return $this->itemId;
    }

    public function name(): FileSubtypeName
    {
        return $this->name;
    }

    public function description(): FileSubtypeDescription
    {
        return $this->description;
    }

    public function settings(): FileSubtypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?FileSubtypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FileSubtypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?FileSubtypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?FileSubtypeRelationships
    {
        return $this->relationships;
    }
}
