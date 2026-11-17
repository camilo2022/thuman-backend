<?php

declare(strict_types=1);

namespace App\Application\PersonUseCase;

use App\Domain\Person\Contracts\PersonContract;
use App\Domain\Person\Person;
use App\Domain\Person\Values\PersonAddress;
use App\Domain\Person\Values\PersonBirthCityId;
use App\Domain\Person\Values\PersonBirthDate;
use App\Domain\Person\Values\PersonBloodTypeId;
use App\Domain\Person\Values\PersonCityId;
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
use App\Domain\Person\Values\PersonSecondLastName;
use App\Domain\Person\Values\PersonSecondName;
use App\Domain\Person\Values\PersonSexId;
use App\Domain\Person\Values\PersonVacantId;
use App\Http\Requests\Person\PersonToggleRequest;

final class PersonToggleUseCase
{
    private $personContract;

    public function __construct(PersonContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(PersonToggleRequest $request, PersonId $personId): Person
    {
        return $this->personContract->toggle(
            new Person(
                new PersonId($personId->value()),
                new PersonFirstName(''),
                new PersonSecondName(''),
                new PersonFirstLastName(''),
                new PersonSecondLastName(''),
                new PersonBirthDate(''),
                new PersonBirthCityId(0),
                new PersonDocumentNumber(''),
                new PersonDocumentIssueCityId(0),
                new PersonDocumentIssueDate(''),
                new PersonCityId(0),
                new PersonAddress(''),
                new PersonNeighborhood(''),
                new PersonDialingCountryId(0),
                new PersonPhoneNumber(''),
                new PersonPhoneNumberVerifiedAt(null),
                new PersonEmail(''),
                new PersonEmailVerifiedAt(null),
                new PersonDocumentTypeId(0),
                new PersonSexId(0),
                new PersonGenderId(0),
                new PersonBloodTypeId(0),
                new PersonMaritalStatusId(0),
                new PersonVacantId($request->integer('vacant_id')),
                new PersonEntityId($request->integer('entity_id'))
            ),
            $personId
        );
    }
}
