<?php

declare(strict_types=1);

namespace App\Application\PersonUseCase;

use App\Domain\Person\Person;
use App\Domain\Person\Contracts\PersonContract;
use App\Domain\Person\Values\PersonId;

final class PersonDeleteUseCase
{
    private $enterpriseContract;

    public function __construct(PersonContract $enterpriseContract)
    {
        $this->enterpriseContract = $enterpriseContract;
    }

    public function __invoke(PersonId $enterpriseId): ?Person
    {
        return $this->enterpriseContract->delete($enterpriseId);
    }
}
