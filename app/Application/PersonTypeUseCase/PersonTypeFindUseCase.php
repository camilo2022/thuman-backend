<?php

declare(strict_types=1);

namespace App\Application\PersonTypeUseCase;

use App\Domain\PersonType\PersonType;
use App\Domain\PersonType\Contracts\PersonTypeContract;
use App\Domain\PersonType\Values\PersonTypeId;

final class PersonTypeFindUseCase
{
    private $personTypeContract;

    public function __construct(PersonTypeContract $personTypeContract)
    {
        $this->personTypeContract = $personTypeContract;
    }

    public function __invoke(PersonTypeId $personTypeId): ?PersonType
    {
        return $this->personTypeContract->find($personTypeId);
    }
}
