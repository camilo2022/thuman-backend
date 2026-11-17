<?php

declare(strict_types=1);

namespace App\Application\VacationUseCase;

use App\Domain\Vacation\Vacation;
use App\Domain\Vacation\Contracts\VacationContract;
use App\Domain\Vacation\Values\VacationId;

final class VacationFindUseCase
{
    private $vacationContract;

    public function __construct(VacationContract $vacationContract)
    {
        $this->vacationContract = $vacationContract;
    }

    public function __invoke(VacationId $vacationId): ?Vacation
    {
        return $this->vacationContract->find($vacationId);
    }
}
