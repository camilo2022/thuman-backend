<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalSkill;

use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillCreatedAt;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillProfileId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillPercentage;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillSkillId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillUpdatedAt;

final class ProfessionalSkill
{
    private $id;
    private $profileId;
    private $skillId;
    private $percentage;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        ?ProfessionalSkillId $id,
        ProfessionalSkillProfileId $profileId,
        ProfessionalSkillSkillId $skillId,
        ProfessionalSkillPercentage $percentage,
        ?ProfessionalSkillCreatedAt $createdAt = null,
        ?ProfessionalSkillUpdatedAt $updatedAt = null
    )
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->skillId = $skillId;
        $this->percentage = $percentage;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?ProfessionalSkillId
    {
        return $this->id;
    }

    public function profileId(): ProfessionalSkillProfileId
    {
        return $this->profileId;
    }

    public function skillId(): ProfessionalSkillSkillId
    {
        return $this->skillId;
    }

    public function percentage(): ProfessionalSkillPercentage
    {
        return $this->percentage;
    }

    public function createdAt(): ?ProfessionalSkillCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ProfessionalSkillUpdatedAt
    {
        return $this->updatedAt;
    }
}
