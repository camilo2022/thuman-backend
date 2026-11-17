<?php

declare(strict_types=1);

namespace App\Application\PersonUseCase;

use App\Domain\Person\Contracts\PersonContract;
use App\Domain\Person\Person;
use App\Domain\Person\Values\PersonId;
use App\Http\Requests\Person\PersonFindRequest;

final class PersonFindUseCase
{
    private $personContract;

    public function __construct(PersonContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(PersonFindRequest $request, PersonId $personId): ?Person
    {
        return $this->personContract->find($request, $personId);
    }
}
