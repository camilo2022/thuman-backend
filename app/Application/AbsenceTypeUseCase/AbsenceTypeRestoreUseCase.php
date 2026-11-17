<?php

declare(strict_types=1);

namespace App\Application\AbsenceTypeUseCase;

use App\Domain\AbsenceType\AbsenceType;
use App\Domain\AbsenceType\Contracts\AbsenceTypeContract;
use App\Domain\AbsenceType\Values\AbsenceTypeId;

final class AbsenceTypeRestoreUseCase
{
    private $absenceTypeContract;

    public function __construct(AbsenceTypeContract $absenceTypeContract)
    {
        $this->absenceTypeContract = $absenceTypeContract;
    }

    public function __invoke(AbsenceTypeId $absenceTypeId): ?AbsenceType
    {
        return $this->absenceTypeContract->restore($absenceTypeId);
    }
}
