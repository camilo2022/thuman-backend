<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\EmergencyContactUseCase;

use App\Domain\Profile\EmergencyContact\Contracts\EmergencyContactContract;
use App\Domain\Profile\EmergencyContact\EmergencyContact;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactDialingCountryId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactFirstLastName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactFirstName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactPhoneNumber;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactProfileId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactRelationshipId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactSecondLastName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactSecondName;
use App\Http\Requests\Profile\EmergencyContact\EmergencyContactStoreRequest;

final class EmergencyContactStoreUseCase
{
    private $emergencyContactContract;

    public function __construct(EmergencyContactContract $emergencyContactContract)
    {
        $this->emergencyContactContract = $emergencyContactContract;
    }

    public function __invoke(EmergencyContactStoreRequest $request): EmergencyContact
    {
        return $this->emergencyContactContract->store(
            new EmergencyContact(
                new EmergencyContactId(null),
                new EmergencyContactProfileId($request->integer('profile_id')),
                new EmergencyContactFirstName($request->input('first_name')),
                new EmergencyContactSecondName($request->input('second_name')),
                new EmergencyContactFirstLastName($request->input('first_last_name')),
                new EmergencyContactSecondLastName($request->input('second_last_name')),
                new EmergencyContactRelationshipId($request->integer('relationship_id')),
                new EmergencyContactDialingCountryId($request->integer('dialing_country_id')),
                new EmergencyContactPhoneNumber($request->input('phone_number'))
            )
        );
    }
}
