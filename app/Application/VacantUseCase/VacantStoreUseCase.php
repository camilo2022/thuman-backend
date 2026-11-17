<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase;

use App\Domain\Vacant\Contracts\VacantContract;
use App\Domain\Vacant\Vacant;
use App\Domain\Vacant\Values\VacantBenefitsId;
use App\Domain\Vacant\Values\VacantContractTypeId;
use App\Domain\Vacant\Values\VacantDescription;
use App\Domain\Vacant\Values\VacantEndDate;
use App\Domain\Vacant\Values\VacantHeadquarterId;
use App\Domain\Vacant\Values\VacantHiringReasonId;
use App\Domain\Vacant\Values\VacantId;
use App\Domain\Vacant\Values\VacantPersonId;
use App\Domain\Vacant\Values\VacantPositionId;
use App\Domain\Vacant\Values\VacantQuantity;
use App\Domain\Vacant\Values\VacantSalaryMax;
use App\Domain\Vacant\Values\VacantSalaryMin;
use App\Domain\Vacant\Values\VacantSettings;
use App\Domain\Vacant\Values\VacantStartDate;
use App\Domain\Vacant\Values\VacantStatus;
use App\Domain\Vacant\Values\VacantUuid;
use App\Domain\Vacant\Values\VacantWorkArrangementId;
use App\Domain\Vacant\Values\VacantWorkDayId;
use App\Models\Vacant as VacantModel;
use App\Http\Requests\Vacant\VacantStoreRequest;

final class VacantStoreUseCase
{
    private $vacantContract;

    public function __construct(VacantContract $vacantContract)
    {
        $this->vacantContract = $vacantContract;
    }

    public function __invoke(VacantStoreRequest $request): Vacant
    {
        return $this->vacantContract->store(
            new Vacant(
                new VacantId(null),
                new VacantUuid(null),
                new VacantHiringReasonId($request->integer('hiring_reason_id')),
                new VacantPositionId($request->integer('position_id')),
                new VacantPersonId($request->integer('person_id')),
                new VacantHeadquarterId($request->integer('headquarter_id')),
                new VacantContractTypeId($request->integer('contract_type_id')),
                new VacantWorkArrangementId($request->integer('work_arrangement_id')),
                new VacantWorkDayId($request->integer('work_day_id')),
                new VacantSalaryMin((float) $request->get('salary_min')),
                new VacantSalaryMax((float) $request->get('salary_max')),
                new VacantStartDate($request->input('start_date')),
                new VacantEndDate($request->input('end_date')),
                new VacantDescription($request->input('description')),
                new VacantQuantity($request->integer('quantity')),
                new VacantStatus(VacantModel::REVIEW),
                new VacantSettings($request->input('settings')),
                new VacantBenefitsId($request->input('benefits_id'))
            )
        );
    }
}
