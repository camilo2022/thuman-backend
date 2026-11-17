<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification;

use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicInstitution;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicLevelId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicTitle;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationCityId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationCreatedAt;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationEndDate;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationFormationStatusId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationProfileId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationRelationships;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationStartDate;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationUpdatedAt;

final class AcademicQualification
{
    private $id;
    private $profileId;
    private $cityId;
    private $academicLevelId;
    private $academicInstitution;
    private $academicTitle;
    private $formationStatusId;
    private $startDate;
    private $endDate;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?AcademicQualificationId $id,
        AcademicQualificationProfileId $profileId,
        AcademicQualificationCityId $cityId,
        AcademicQualificationAcademicLevelId $academicLevelId,
        AcademicQualificationAcademicInstitution $academicInstitution,
        AcademicQualificationAcademicTitle $academicTitle,
        AcademicQualificationFormationStatusId $formationStatusId,
        AcademicQualificationStartDate $startDate,
        ?AcademicQualificationEndDate $endDate = null,
        ?AcademicQualificationCreatedAt $createdAt = null,
        ?AcademicQualificationUpdatedAt $updatedAt = null,
        ?AcademicQualificationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->cityId = $cityId;
        $this->academicLevelId = $academicLevelId;
        $this->academicInstitution = $academicInstitution;
        $this->academicTitle = $academicTitle;
        $this->formationStatusId = $formationStatusId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?AcademicQualificationId
    {
        return $this->id;
    }

    public function profileId(): AcademicQualificationProfileId
    {
        return $this->profileId;
    }

    public function cityId(): AcademicQualificationCityId
    {
        return $this->cityId;
    }

    public function academicLevelId(): AcademicQualificationAcademicLevelId
    {
        return $this->academicLevelId;
    }

    public function academicInstitution(): AcademicQualificationAcademicInstitution
    {
        return $this->academicInstitution;
    }

    public function academicTitle(): AcademicQualificationAcademicTitle
    {
        return $this->academicTitle;
    }

    public function formationStatusId(): AcademicQualificationFormationStatusId
    {
        return $this->formationStatusId;
    }

    public function startDate(): AcademicQualificationStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ?AcademicQualificationEndDate
    {
        return $this->endDate;
    }

    public function createdAt(): ?AcademicQualificationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AcademicQualificationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?AcademicQualificationRelationships
    {
        return $this->relationships;
    }
}
