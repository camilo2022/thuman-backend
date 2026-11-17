<?php

declare(strict_types=1);

namespace App\Application\VacationTypeUseCase;

use App\Domain\VacationType\VacationType;
use App\Domain\VacationType\Contracts\VacationTypeContract;
use App\Domain\VacationType\Values\VacationTypeId;

final class VacationTypeRestoreUseCase
{
    private $vacationTypeContract;

    public function __construct(VacationTypeContract $vacationTypeContract)
    {
        $this->vacationTypeContract = $vacationTypeContract;
    }

    public function __invoke(VacationTypeId $vacationTypeId): ?VacationType
    {
        return $this->vacationTypeContract->restore($vacationTypeId);
    }
}
