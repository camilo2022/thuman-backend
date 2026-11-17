<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredExperience;

use App\Domain\Position\RequiredExperience\Values\RequiredExperienceDurationMonths;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceName;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceCreatedAt;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceDescription;
use App\Domain\Position\RequiredExperience\Values\RequiredExperiencePositionId;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceUpdatedAt;

final class RequiredExperience
{
    private $id;
    private $positionId;
    private $name;
    private $durationMonths;
    private $description;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        ?RequiredExperienceId $id,
        RequiredExperiencePositionId $positionId,
        RequiredExperienceName $name,
        RequiredExperienceDurationMonths $durationMonths,
        RequiredExperienceDescription $description,
        ?RequiredExperienceCreatedAt $createdAt = null,
        ?RequiredExperienceUpdatedAt $updatedAt = null
    )
    {
        $this->id = $id;
        $this->positionId = $positionId;
        $this->name = $name;
        $this->durationMonths = $durationMonths;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?RequiredExperienceId
    {
        return $this->id;
    }

    public function positionId(): RequiredExperiencePositionId
    {
        return $this->positionId;
    }

    public function name(): RequiredExperienceName
    {
        return $this->name;
    }

    public function durationMonths(): RequiredExperienceDurationMonths
    {
        return $this->durationMonths;
    }

    public function description(): RequiredExperienceDescription
    {
        return $this->description;
    }

    public function createdAt(): ?RequiredExperienceCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?RequiredExperienceUpdatedAt
    {
        return $this->updatedAt;
    }
}
