<?php

declare(strict_types=1);

namespace App\Domain\SkillType;

use App\Domain\SkillType\Values\SkillTypeCreatedAt;
use App\Domain\SkillType\Values\SkillTypeDeletedAt;
use App\Domain\SkillType\Values\SkillTypeDescription;
use App\Domain\SkillType\Values\SkillTypeId;
use App\Domain\SkillType\Values\SkillTypeItemId;
use App\Domain\SkillType\Values\SkillTypeName;
use App\Domain\SkillType\Values\SkillTypeSettings;
use App\Domain\SkillType\Values\SkillTypeUpdatedAt;

final class SkillType
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
        ?SkillTypeId $id,
        SkillTypeItemId $itemId,
        SkillTypeName $name,
        SkillTypeDescription $description,
        SkillTypeSettings $settings,
        ?SkillTypeCreatedAt $createdAt = null,
        ?SkillTypeUpdatedAt $updatedAt = null,
        ?SkillTypeDeletedAt $deletedAt = null
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

    public function id(): ?SkillTypeId
    {
        return $this->id;
    }

    public function itemId(): SkillTypeItemId
    {
        return $this->itemId;
    }

    public function name(): SkillTypeName
    {
        return $this->name;
    }

    public function description(): SkillTypeDescription
    {
        return $this->description;
    }

    public function settings(): SkillTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?SkillTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SkillTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?SkillTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
