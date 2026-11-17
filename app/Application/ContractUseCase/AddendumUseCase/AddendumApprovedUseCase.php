<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\AddendumUseCase;

use App\Domain\Contract\Addendum\Contracts\AddendumContract;
use App\Domain\Contract\Addendum\Addendum;
use App\Domain\Contract\Addendum\Values\AddendumAddendumTypeId;
use App\Domain\Contract\Addendum\Values\AddendumContractId;
use App\Domain\Contract\Addendum\Values\AddendumContractTypeId;
use App\Domain\Contract\Addendum\Values\AddendumEndDate;
use App\Domain\Contract\Addendum\Values\AddendumFileSubtypesId;
use App\Domain\Contract\Addendum\Values\AddendumHeadquarterId;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeStatus;
use App\Domain\Contract\Addendum\Values\AddendumPaymentPeriodId;
use App\Domain\Contract\Addendum\Values\AddendumPersonObservation;
use App\Domain\Contract\Addendum\Values\AddendumPersonSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumPersonStatus;
use App\Domain\Contract\Addendum\Values\AddendumPositionId;
use App\Domain\Contract\Addendum\Values\AddendumSalary;
use App\Domain\Contract\Addendum\Values\AddendumSettings;
use App\Domain\Contract\Addendum\Values\AddendumStartDate;
use App\Domain\Contract\Addendum\Values\AddendumUuid;
use App\Http\Requests\Contract\Addendum\AddendumApprovedRequest;
use App\Models\Addendum as AddendumModel;

final class AddendumApprovedUseCase
{
    private $addendumContract;

    public function __construct(AddendumContract $addendumContract)
    {
        $this->addendumContract = $addendumContract;
    }

    public function __invoke(AddendumApprovedRequest $request, AddendumId $addendumId): Addendum
    {
        return $this->addendumContract->update(
            new Addendum(
                new AddendumId($addendumId->value()),
                new AddendumUuid(null),
                new AddendumContractId($request->integer('contract_id')),
                new AddendumAddendumTypeId($request->integer('addendum_type_id')),
                new AddendumHeadquarterId($request->integer('headquarter_id')),
                new AddendumContractTypeId($request->integer('contract_type_id')),
                new AddendumPositionId($request->integer('position_id')),
                new AddendumSalary((float) $request->get('salary')),
                new AddendumPaymentPeriodId($request->integer('payment_period_id')),
                new AddendumStartDate($request->input('start_date')),
                new AddendumEndDate($request->input('end_date')),
                new AddendumPersonObservation($request->input('person_observation')),
                new AddendumPersonSignedAt($request->input('person_signed_at')),
                new AddendumPersonStatus(AddendumModel::PENDING),
                new AddendumLegalRepresentativeSignedAt($request->input('legal_representative_signed_at')),
                new AddendumLegalRepresentativeStatus(AddendumModel::APPROVED),
                new AddendumSettings($request->input('settings')),
                new AddendumFileSubtypesId($request->input('file_subtypes_id'))
            ),
            $addendumId
        );
    }
}
