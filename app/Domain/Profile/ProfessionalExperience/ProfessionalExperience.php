<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience;

use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCityId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCompanyAddress;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCompanyName;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCreatedAt;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceEndDate;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceWorkArrangementId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperiencePosition;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperiencePositionDescription;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceProfileId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceRelationships;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceStartDate;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorDialingCountryId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorName;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorPhoneNumber;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceUpdatedAt;

final class ProfessionalExperience
{
    private $id;
    private $profileId;
    private $companyName;
    private $supervisorName;
    private $supervisorDialingCountryId;
    private $supervisorPhoneNumber;
    private $cityId;
    private $companyAddress;
    private $startDate;
    private $endDate;
    private $position;
    private $workArrangementId;
    private $positionDescription;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?ProfessionalExperienceId $id,
        ProfessionalExperienceProfileId $profileId,
        ProfessionalExperienceCompanyName $companyName,
        ProfessionalExperienceSupervisorName $supervisorName,
        ProfessionalExperienceSupervisorDialingCountryId $supervisorDialingCountryId,
        ProfessionalExperienceSupervisorPhoneNumber $supervisorPhoneNumber,
        ProfessionalExperienceCityId $cityId,
        ProfessionalExperienceCompanyAddress $companyAddress,
        ProfessionalExperienceStartDate $startDate,
        ?ProfessionalExperienceEndDate $endDate = null,
        ProfessionalExperiencePosition $position,
        ProfessionalExperienceWorkArrangementId $workArrangementId,
        ProfessionalExperiencePositionDescription $positionDescription,
        ?ProfessionalExperienceCreatedAt $createdAt = null,
        ?ProfessionalExperienceUpdatedAt $updatedAt = null,
        ?ProfessionalExperienceRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->companyName = $companyName;
        $this->supervisorName = $supervisorName;
        $this->supervisorDialingCountryId = $supervisorDialingCountryId;
        $this->supervisorPhoneNumber = $supervisorPhoneNumber;
        $this->cityId = $cityId;
        $this->companyAddress = $companyAddress;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->position = $position;
        $this->workArrangementId = $workArrangementId;
        $this->positionDescription = $positionDescription;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ProfessionalExperienceId
    {
        return $this->id;
    }

    public function profileId(): ProfessionalExperienceProfileId
    {
        return $this->profileId;
    }

    public function companyName(): ProfessionalExperienceCompanyName
    {
        return $this->companyName;
    }

    public function supervisorName(): ProfessionalExperienceSupervisorName
    {
        return $this->supervisorName;
    }

    public function supervisorDialingCountryId(): ProfessionalExperienceSupervisorDialingCountryId
    {
        return $this->supervisorDialingCountryId;
    }

    public function supervisorPhoneNumber(): ProfessionalExperienceSupervisorPhoneNumber
    {
        return $this->supervisorPhoneNumber;
    }

    public function cityId(): ProfessionalExperienceCityId
    {
        return $this->cityId;
    }

    public function companyAddress(): ProfessionalExperienceCompanyAddress
    {
        return $this->companyAddress;
    }

    public function startDate(): ProfessionalExperienceStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ProfessionalExperienceEndDate
    {
        return $this->endDate;
    }

    public function position(): ProfessionalExperiencePosition
    {
        return $this->position;
    }

    public function workArrangementId(): ProfessionalExperienceWorkArrangementId
    {
        return $this->workArrangementId;
    }

    public function positionDescription(): ProfessionalExperiencePositionDescription
    {
        return $this->positionDescription;
    }

    public function createdAt(): ?ProfessionalExperienceCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ProfessionalExperienceUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?ProfessionalExperienceRelationships
    {
        return $this->relationships;
    }
}
