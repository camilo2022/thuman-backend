<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupCreatedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupDeletedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupDescription;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupItemId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupName;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupCIEGroupId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupRelationships;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupSettings;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupUpdatedAt;

final class CIESubgroup
{
    private $id;
    private $cieGroupId;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?CIESubgroupId $id,
        CIESubgroupCIEGroupId $cieGroupId,
        CIESubgroupItemId $itemId,
        CIESubgroupName $name,
        CIESubgroupDescription $description,
        CIESubgroupSettings $settings,
        ?CIESubgroupCreatedAt $createdAt = null,
        ?CIESubgroupUpdatedAt $updatedAt = null,
        ?CIESubgroupDeletedAt $deletedAt = null,
        ?CIESubgroupRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->cieGroupId = $cieGroupId;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?CIESubgroupId
    {
        return $this->id;
    }

    public function cieGroupId(): CIESubgroupCIEGroupId
    {
        return $this->cieGroupId;
    }

    public function itemId(): CIESubgroupItemId
    {
        return $this->itemId;
    }

    public function name(): CIESubgroupName
    {
        return $this->name;
    }

    public function description(): CIESubgroupDescription
    {
        return $this->description;
    }

    public function settings(): CIESubgroupSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?CIESubgroupCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CIESubgroupUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CIESubgroupDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?CIESubgroupRelationships
    {
        return $this->relationships;
    }
}
