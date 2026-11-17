<?php

declare(strict_types=1);

namespace App\Application\PersonUseCase;

use App\Domain\Person\Contracts\PersonContract;
use App\Domain\Person\Person;
use App\Domain\Person\Values\PersonId;
use App\Http\Requests\Person\PersonVerifyPhoneNumberRequest;

final class PersonVerifyPhoneNumberUseCase
{
    private $personContract;

    public function __construct(PersonContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(PersonVerifyPhoneNumberRequest $request, PersonId $personId): ?Person
    {
        return $this->personContract->verifyPhoneNumber($request, $personId);
    }
}
