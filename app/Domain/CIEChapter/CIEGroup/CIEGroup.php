<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup;

use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupCreatedAt;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupDeletedAt;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupDescription;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupItemId;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupName;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupCIEChapterId;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupRelationships;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupSettings;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupUpdatedAt;

final class CIEGroup
{
    private $id;
    private $cieChapterId;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?CIEGroupId $id,
        CIEGroupCIEChapterId $cieChapterId,
        CIEGroupItemId $itemId,
        CIEGroupName $name,
        CIEGroupDescription $description,
        CIEGroupSettings $settings,
        ?CIEGroupCreatedAt $createdAt = null,
        ?CIEGroupUpdatedAt $updatedAt = null,
        ?CIEGroupDeletedAt $deletedAt = null,
        ?CIEGroupRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->cieChapterId = $cieChapterId;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?CIEGroupId
    {
        return $this->id;
    }

    public function cieChapterId(): CIEGroupCIEChapterId
    {
        return $this->cieChapterId;
    }

    public function itemId(): CIEGroupItemId
    {
        return $this->itemId;
    }

    public function name(): CIEGroupName
    {
        return $this->name;
    }

    public function description(): CIEGroupDescription
    {
        return $this->description;
    }

    public function settings(): CIEGroupSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?CIEGroupCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CIEGroupUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CIEGroupDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?CIEGroupRelationships
    {
        return $this->relationships;
    }
}
