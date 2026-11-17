<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredSkill;

use App\Domain\Position\RequiredSkill\Values\RequiredSkillCreatedAt;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPositionId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPercentage;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillSkillId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillUpdatedAt;

final class RequiredSkill
{
    private $id;
    private $positionId;
    private $skillId;
    private $percentage;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        ?RequiredSkillId $id,
        RequiredSkillPositionId $positionId,
        RequiredSkillSkillId $skillId,
        RequiredSkillPercentage $percentage,
        ?RequiredSkillCreatedAt $createdAt = null,
        ?RequiredSkillUpdatedAt $updatedAt = null
    )
    {
        $this->id = $id;
        $this->positionId = $positionId;
        $this->skillId = $skillId;
        $this->percentage = $percentage;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?RequiredSkillId
    {
        return $this->id;
    }

    public function positionId(): RequiredSkillPositionId
    {
        return $this->positionId;
    }

    public function skillId(): RequiredSkillSkillId
    {
        return $this->skillId;
    }

    public function percentage(): RequiredSkillPercentage
    {
        return $this->percentage;
    }

    public function createdAt(): ?RequiredSkillCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?RequiredSkillUpdatedAt
    {
        return $this->updatedAt;
    }
}
