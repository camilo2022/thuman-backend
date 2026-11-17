<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeCreatedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeDeletedAt;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeDescription;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeItemId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeName;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeCIESubgroupId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeRelationships;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeSettings;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeUpdatedAt;

final class CIECode
{
    private $id;
    private $cieSubgroupId;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?CIECodeId $id,
        CIECodeCIESubgroupId $cieSubgroupId,
        CIECodeItemId $itemId,
        CIECodeName $name,
        CIECodeDescription $description,
        CIECodeSettings $settings,
        ?CIECodeCreatedAt $createdAt = null,
        ?CIECodeUpdatedAt $updatedAt = null,
        ?CIECodeDeletedAt $deletedAt = null,
        ?CIECodeRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->cieSubgroupId = $cieSubgroupId;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?CIECodeId
    {
        return $this->id;
    }

    public function cieSubgroupId(): CIECodeCIESubgroupId
    {
        return $this->cieSubgroupId;
    }

    public function itemId(): CIECodeItemId
    {
        return $this->itemId;
    }

    public function name(): CIECodeName
    {
        return $this->name;
    }

    public function description(): CIECodeDescription
    {
        return $this->description;
    }

    public function settings(): CIECodeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?CIECodeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CIECodeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CIECodeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?CIECodeRelationships
    {
        return $this->relationships;
    }
}
