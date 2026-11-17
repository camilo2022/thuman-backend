<?php

declare(strict_types=1);

namespace App\Application\AbsenceUseCase;

use App\Domain\Absence\Absence;
use App\Domain\Absence\Contracts\AbsenceContract;
use App\Domain\Absence\Values\AbsenceId;

final class AbsenceFindUseCase
{
    private $absenceContract;

    public function __construct(AbsenceContract $absenceContract)
    {
        $this->absenceContract = $absenceContract;
    }

    public function __invoke(AbsenceId $absenceId): ?Absence
    {
        return $this->absenceContract->find($absenceId);
    }
}
