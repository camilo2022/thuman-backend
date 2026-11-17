<?php

declare(strict_types=1);

namespace App\Application\PersonUseCase;

use App\Domain\Person\Contracts\PersonContract;
use App\Domain\Person\Person;
use App\Domain\Person\Values\PersonId;

final class PersonNotifyEmailUseCase
{
    private $personContract;

    public function __construct(PersonContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(PersonId $personId): ?Person
    {
        return $this->personContract->notifyEmail($personId);
    }
}
