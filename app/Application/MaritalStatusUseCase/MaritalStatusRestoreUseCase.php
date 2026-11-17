<?php

declare(strict_types=1);

namespace App\Application\MaritalStatusUseCase;

use App\Domain\MaritalStatus\MaritalStatus;
use App\Domain\MaritalStatus\Contracts\MaritalStatusContract;
use App\Domain\MaritalStatus\Values\MaritalStatusId;

final class MaritalStatusRestoreUseCase
{
    private $maritalStatusContract;

    public function __construct(MaritalStatusContract $maritalStatusContract)
    {
        $this->maritalStatusContract = $maritalStatusContract;
    }

    public function __invoke(MaritalStatusId $maritalStatusId): ?MaritalStatus
    {
        return $this->maritalStatusContract->restore($maritalStatusId);
    }
}
