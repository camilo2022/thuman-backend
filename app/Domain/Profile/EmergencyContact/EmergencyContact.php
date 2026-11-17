<?php

declare(strict_types=1);

namespace App\Domain\Profile\EmergencyContact;

use App\Domain\Profile\EmergencyContact\Values\EmergencyContactCreatedAt;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactDialingCountryId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactFirstLastName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactFirstName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactProfileId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactPhoneNumber;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactRelationshipId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactRelationships;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactSecondLastName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactSecondName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactUpdatedAt;

final class EmergencyContact
{
    private $id;
    private $profileId;
    private $firstName;
    private $secondName;
    private $firstLastName;
    private $secondLastName;
    private $relationshipId;
    private $dialingCountryId;
    private $phoneNumber;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?EmergencyContactId $id,
        EmergencyContactProfileId $profileId,
        EmergencyContactFirstName $firstName,
        EmergencyContactSecondName $secondName,
        EmergencyContactFirstLastName $firstLastName,
        EmergencyContactSecondLastName $secondLastName,
        EmergencyContactRelationshipId $relationshipId,
        EmergencyContactDialingCountryId $dialingCountryId,
        EmergencyContactPhoneNumber $phoneNumber,
        ?EmergencyContactCreatedAt $createdAt = null,
        ?EmergencyContactUpdatedAt $updatedAt = null,
        ?EmergencyContactRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->firstLastName = $firstLastName;
        $this->secondLastName = $secondLastName;
        $this->relationshipId = $relationshipId;
        $this->dialingCountryId = $dialingCountryId;
        $this->phoneNumber = $phoneNumber;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?EmergencyContactId
    {
        return $this->id;
    }

    public function profileId(): EmergencyContactProfileId
    {
        return $this->profileId;
    }

    public function firstName(): EmergencyContactFirstName
    {
        return $this->firstName;
    }

    public function secondName(): EmergencyContactSecondName
    {
        return $this->secondName;
    }

    public function firstLastName(): EmergencyContactFirstLastName
    {
        return $this->firstLastName;
    }

    public function secondLastName(): EmergencyContactSecondLastName
    {
        return $this->secondLastName;
    }

    public function relationshipId(): EmergencyContactRelationshipId
    {
        return $this->relationshipId;
    }

    public function dialingCountryId(): EmergencyContactDialingCountryId
    {
        return $this->dialingCountryId;
    }

    public function phoneNumber(): EmergencyContactPhoneNumber
    {
        return $this->phoneNumber;
    }

    public function createdAt(): ?EmergencyContactCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?EmergencyContactUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?EmergencyContactRelationships
    {
        return $this->relationships;
    }
}
