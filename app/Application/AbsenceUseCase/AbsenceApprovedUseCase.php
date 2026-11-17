<?php

declare(strict_types=1);

namespace App\Application\AbsenceUseCase;

use App\Domain\Absence\Contracts\AbsenceContract;
use App\Domain\Absence\Absence;
use App\Domain\Absence\Values\AbsenceAbsenceTypeId;
use App\Domain\Absence\Values\AbsenceCompensation;
use App\Domain\Absence\Values\AbsenceEndDate;
use App\Domain\Absence\Values\AbsenceId;
use App\Domain\Absence\Values\AbsencePersonId;
use App\Domain\Absence\Values\AbsenceReason;
use App\Domain\Absence\Values\AbsenceStartDate;
use App\Domain\Absence\Values\AbsenceStatus;
use App\Models\Absence as AbsenceModel;
use App\Http\Requests\Absence\AbsenceApprovedRequest;

final class AbsenceApprovedUseCase
{
    private $absenceContract;

    public function __construct(AbsenceContract $absenceContract)
    {
        $this->absenceContract = $absenceContract;
    }

    public function __invoke(AbsenceApprovedRequest $request, AbsenceId $absenceId): Absence
    {
        return $this->absenceContract->update(
            new Absence(
                new AbsenceId($absenceId->value()),
                new AbsencePersonId($request->integer('person_id')),
                new AbsenceAbsenceTypeId($request->integer('absence_type_id')),
                new AbsenceStartDate($request->input('start_date')),
                new AbsenceEndDate($request->input('end_date')),
                new AbsenceReason($request->input('reason')),
                new AbsenceCompensation($request->input('compensation')),
                new AbsenceStatus(AbsenceModel::APPROVED)
            ),
            $absenceId
        );
    }
}
