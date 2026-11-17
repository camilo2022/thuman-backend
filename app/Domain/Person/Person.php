<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Person\Values\PersonAddress;
use App\Domain\Person\Values\PersonBirthCityId;
use App\Domain\Person\Values\PersonBirthDate;
use App\Domain\Person\Values\PersonBloodTypeId;
use App\Domain\Person\Values\PersonCityId;
use App\Domain\Person\Values\PersonCreatedAt;
use App\Domain\Person\Values\PersonDeletedAt;
use App\Domain\Person\Values\PersonDialingCountryId;
use App\Domain\Person\Values\PersonDocumentIssueCityId;
use App\Domain\Person\Values\PersonDocumentIssueDate;
use App\Domain\Person\Values\PersonDocumentNumber;
use App\Domain\Person\Values\PersonDocumentTypeId;
use App\Domain\Person\Values\PersonEmail;
use App\Domain\Person\Values\PersonEmailVerifiedAt;
use App\Domain\Person\Values\PersonEntityId;
use App\Domain\Person\Values\PersonFirstLastName;
use App\Domain\Person\Values\PersonFirstName;
use App\Domain\Person\Values\PersonGenderId;
use App\Domain\Person\Values\PersonId;
use App\Domain\Person\Values\PersonMaritalStatusId;
use App\Domain\Person\Values\PersonNeighborhood;
use App\Domain\Person\Values\PersonPhoneNumber;
use App\Domain\Person\Values\PersonPhoneNumberVerifiedAt;
use App\Domain\Person\Values\PersonRelationships;
use App\Domain\Person\Values\PersonSecondLastName;
use App\Domain\Person\Values\PersonSecondName;
use App\Domain\Person\Values\PersonSexId;
use App\Domain\Person\Values\PersonUpdatedAt;
use App\Domain\Person\Values\PersonVacantId;

final class Person
{
    private $id;
    private $firstName;
    private $secondName;
    private $firstLastName;
    private $secondLastName;
    private $birthDate;
    private $birthCityId;
    private $documentNumber;
    private $documentIssueCityId;
    private $documentIssueDate;
    private $cityId;
    private $address;
    private $neighborhood;
    private $dialingCountryId;
    private $phoneNumber;
    private $phoneNumberVerifiedAt;
    private $email;
    private $emailVerifiedAt;
    private $documentTypeId;
    private $sexId;
    private $genderId;
    private $bloodTypeId;
    private $maritalStatusId;
    private $vacantId;
    private $entityId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?PersonId $id,
        PersonFirstName $firstName,
        ?PersonSecondName $secondName,
        PersonFirstLastName $firstLastName,
        ?PersonSecondLastName $secondLastName,
        ?PersonBirthDate $birthDate,
        ?PersonBirthCityId $birthCityId,
        PersonDocumentNumber $documentNumber,
        ?PersonDocumentIssueCityId $documentIssueCityId,
        ?PersonDocumentIssueDate $documentIssueDate,
        ?PersonCityId $cityId,
        ?PersonAddress $address,
        ?PersonNeighborhood $neighborhood,
        PersonDialingCountryId $dialingCountryId,
        PersonPhoneNumber $phoneNumber,
        ?PersonPhoneNumberVerifiedAt $phoneNumberVerifiedAt,
        PersonEmail $email,
        ?PersonEmailVerifiedAt $emailVerifiedAt,
        PersonDocumentTypeId $documentTypeId,
        ?PersonSexId $sexId,
        ?PersonGenderId $genderId,
        ?PersonBloodTypeId $bloodTypeId,
        ?PersonMaritalStatusId $maritalStatusId,
        ?PersonVacantId $vacantId = null,
        ?PersonEntityId $entityId = null,
        ?PersonCreatedAt $createdAt = null,
        ?PersonUpdatedAt $updatedAt = null,
        ?PersonDeletedAt $deletedAt = null,
        ?PersonRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->firstLastName = $firstLastName;
        $this->secondLastName = $secondLastName;
        $this->birthDate = $birthDate;
        $this->birthCityId = $birthCityId;
        $this->documentNumber = $documentNumber;
        $this->documentIssueCityId = $documentIssueCityId;
        $this->documentIssueDate = $documentIssueDate;
        $this->cityId = $cityId;
        $this->address = $address;
        $this->neighborhood = $neighborhood;
        $this->dialingCountryId = $dialingCountryId;
        $this->phoneNumber = $phoneNumber;
        $this->phoneNumberVerifiedAt = $phoneNumberVerifiedAt;
        $this->email = $email;
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->documentTypeId = $documentTypeId;
        $this->sexId = $sexId;
        $this->genderId = $genderId;
        $this->bloodTypeId = $bloodTypeId;
        $this->maritalStatusId = $maritalStatusId;
        $this->vacantId = $vacantId;
        $this->entityId = $entityId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?PersonId
    {
        return $this->id;
    }

    public function firstName(): PersonFirstName
    {
        return $this->firstName;
    }

    public function secondName(): PersonSecondName
    {
        return $this->secondName;
    }

    public function firstLastName(): PersonFirstLastName
    {
        return $this->firstLastName;
    }

    public function secondLastName(): PersonSecondLastName
    {
        return $this->secondLastName;
    }

    public function birthDate(): PersonBirthDate
    {
        return $this->birthDate;
    }

    public function birthCityId(): PersonBirthCityId
    {
        return $this->birthCityId;
    }

    public function documentNumber(): PersonDocumentNumber
    {
        return $this->documentNumber;
    }

    public function documentIssueCityId(): PersonDocumentIssueCityId
    {
        return $this->documentIssueCityId;
    }

    public function documentIssueDate(): PersonDocumentIssueDate
    {
        return $this->documentIssueDate;
    }

    public function cityId(): PersonCityId
    {
        return $this->cityId;
    }

    public function address(): PersonAddress
    {
        return $this->address;
    }

    public function neighborhood(): PersonNeighborhood
    {
        return $this->neighborhood;
    }

    public function dialingCountryId(): PersonDialingCountryId
    {
        return $this->dialingCountryId;
    }

    public function phoneNumber(): PersonPhoneNumber
    {
        return $this->phoneNumber;
    }

    public function phoneNumberVerifiedAt(): PersonPhoneNumberVerifiedAt
    {
        return $this->phoneNumberVerifiedAt;
    }

    public function email(): PersonEmail
    {
        return $this->email;
    }

    public function emailVerifiedAt(): PersonEmailVerifiedAt
    {
        return $this->emailVerifiedAt;
    }

    public function documentTypeId(): PersonDocumentTypeId
    {
        return $this->documentTypeId;
    }

    public function sexId(): PersonSexId
    {
        return $this->sexId;
    }

    public function genderId(): PersonGenderId
    {
        return $this->genderId;
    }

    public function bloodTypeId(): PersonBloodTypeId
    {
        return $this->bloodTypeId;
    }

    public function maritalStatusId(): PersonMaritalStatusId
    {
        return $this->maritalStatusId;
    }

    public function vacantId(): PersonVacantId
    {
        return $this->vacantId;
    }

    public function entityId(): PersonEntityId
    {
        return $this->entityId;
    }

    public function createdAt(): ?PersonCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PersonUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?PersonDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?PersonRelationships
    {
        return $this->relationships;
    }
}
