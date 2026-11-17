<?php

declare(strict_types=1);

namespace App\Domain\Relationship;

use App\Domain\Relationship\Values\RelationshipCreatedAt;
use App\Domain\Relationship\Values\RelationshipDeletedAt;
use App\Domain\Relationship\Values\RelationshipDescription;
use App\Domain\Relationship\Values\RelationshipId;
use App\Domain\Relationship\Values\RelationshipItemId;
use App\Domain\Relationship\Values\RelationshipName;
use App\Domain\Relationship\Values\RelationshipSettings;
use App\Domain\Relationship\Values\RelationshipUpdatedAt;

final class Relationship
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?RelationshipId $id,
        RelationshipItemId $itemId,
        RelationshipName $name,
        RelationshipDescription $description,
        RelationshipSettings $settings,
        ?RelationshipCreatedAt $createdAt = null,
        ?RelationshipUpdatedAt $updatedAt = null,
        ?RelationshipDeletedAt $deletedAt = null
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
    }

    public function id(): ?RelationshipId
    {
        return $this->id;
    }

    public function itemId(): RelationshipItemId
    {
        return $this->itemId;
    }

    public function name(): RelationshipName
    {
        return $this->name;
    }

    public function description(): RelationshipDescription
    {
        return $this->description;
    }

    public function settings(): RelationshipSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?RelationshipCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?RelationshipUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?RelationshipDeletedAt
    {
        return $this->deletedAt;
    }
}
