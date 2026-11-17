<?php

declare(strict_types=1);

namespace App\Application\VacationUseCase;

use App\Domain\Vacation\Contracts\VacationContract;
use App\Domain\Vacation\Vacation;
use App\Domain\Vacation\Values\VacationDays;
use App\Domain\Vacation\Values\VacationVacationTypeId;
use App\Domain\Vacation\Values\VacationEndDate;
use App\Domain\Vacation\Values\VacationId;
use App\Domain\Vacation\Values\VacationPeriod;
use App\Domain\Vacation\Values\VacationPersonId;
use App\Domain\Vacation\Values\VacationStartDate;
use App\Domain\Vacation\Values\VacationStatus;
use App\Models\Vacation as VacationModel;
use App\Http\Requests\Vacation\VacationRejectedRequest;

final class VacationRejectedUseCase
{
    private $vacationContract;

    public function __construct(VacationContract $vacationContract)
    {
        $this->vacationContract = $vacationContract;
    }

    public function __invoke(VacationRejectedRequest $request, VacationId $vacationId): Vacation
    {
        return $this->vacationContract->update(
            new Vacation(
                new VacationId($vacationId->value()),
                new VacationPersonId($request->integer('person_id')),
                new VacationVacationTypeId($request->integer('vacation_type_id')),
                new VacationPeriod($request->integer('period')),
                new VacationDays($request->integer('days')),
                new VacationStartDate($request->input('start_date')),
                new VacationEndDate($request->input('end_date')),
                new VacationStatus(VacationModel::REJECTED)
            ),
            $vacationId
        );
    }
}
