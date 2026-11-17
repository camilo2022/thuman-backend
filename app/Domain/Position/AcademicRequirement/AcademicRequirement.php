<?php

declare(strict_types=1);

namespace App\Domain\Position\AcademicRequirement;

use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementAcademicLevelId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementAcademicTitle;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementCreatedAt;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementFormationStatusId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementPositionId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementUpdatedAt;

final class AcademicRequirement
{
    private $id;
    private $positionId;
    private $academicLevelId;
    private $academicTitle;
    private $formationStatusId;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        ?AcademicRequirementId $id,
        AcademicRequirementPositionId $positionId,
        AcademicRequirementAcademicLevelId $academicLevelId,
        AcademicRequirementAcademicTitle $academicTitle,
        AcademicRequirementFormationStatusId $formationStatusId,
        ?AcademicRequirementCreatedAt $createdAt = null,
        ?AcademicRequirementUpdatedAt $updatedAt = null
    )
    {
        $this->id = $id;
        $this->positionId = $positionId;
        $this->academicLevelId = $academicLevelId;
        $this->academicTitle = $academicTitle;
        $this->formationStatusId = $formationStatusId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?AcademicRequirementId
    {
        return $this->id;
    }

    public function positionId(): AcademicRequirementPositionId
    {
        return $this->positionId;
    }

    public function academicLevelId(): AcademicRequirementAcademicLevelId
    {
        return $this->academicLevelId;
    }

    public function academicTitle(): AcademicRequirementAcademicTitle
    {
        return $this->academicTitle;
    }

    public function formationStatusId(): AcademicRequirementFormationStatusId
    {
        return $this->formationStatusId;
    }

    public function createdAt(): ?AcademicRequirementCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AcademicRequirementUpdatedAt
    {
        return $this->updatedAt;
    }
}
