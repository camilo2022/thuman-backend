<?php

declare(strict_types=1);

namespace App\Domain\Skill;

use App\Domain\Skill\Values\SkillCreatedAt;
use App\Domain\Skill\Values\SkillDeletedAt;
use App\Domain\Skill\Values\SkillDescription;
use App\Domain\Skill\Values\SkillId;
use App\Domain\Skill\Values\SkillItemId;
use App\Domain\Skill\Values\SkillName;
use App\Domain\Skill\Values\SkillSettings;
use App\Domain\Skill\Values\SkillSkillTypeId;
use App\Domain\Skill\Values\SkillUpdatedAt;

final class Skill
{
    private $id;
    private $skillTypeId;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?SkillId $id,
        SkillSkillTypeId $skillTypeId,
        SkillItemId $itemId,
        SkillName $name,
        SkillDescription $description,
        SkillSettings $settings,
        ?SkillCreatedAt $createdAt = null,
        ?SkillUpdatedAt $updatedAt = null,
        ?SkillDeletedAt $deletedAt = null
    )
    {
        $this->id = $id;
        $this->skillTypeId = $skillTypeId;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function id(): ?SkillId
    {
        return $this->id;
    }

    public function skillTypeId(): SkillSkillTypeId
    {
        return $this->skillTypeId;
    }

    public function itemId(): SkillItemId
    {
        return $this->itemId;
    }

    public function name(): SkillName
    {
        return $this->name;
    }

    public function description(): SkillDescription
    {
        return $this->description;
    }

    public function settings(): SkillSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?SkillCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SkillUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?SkillDeletedAt
    {
        return $this->deletedAt;
    }
}
