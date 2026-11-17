<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase;

use App\Domain\Contract\Contracts\ContractContract;
use App\Domain\Contract\Contract;
use App\Domain\Contract\Values\ContractApplicationId;
use App\Domain\Contract\Values\ContractContractTypeId;
use App\Domain\Contract\Values\ContractEndDate;
use App\Domain\Contract\Values\ContractFileSubtypesId;
use App\Domain\Contract\Values\ContractHeadquarterId;
use App\Domain\Contract\Values\ContractId;
use App\Domain\Contract\Values\ContractLegalRepresentativeSignedAt;
use App\Domain\Contract\Values\ContractLegalRepresentativeStatus;
use App\Domain\Contract\Values\ContractPaymentPeriodId;
use App\Domain\Contract\Values\ContractPersonId;
use App\Domain\Contract\Values\ContractPersonObservation;
use App\Domain\Contract\Values\ContractPersonSignedAt;
use App\Domain\Contract\Values\ContractPersonStatus;
use App\Domain\Contract\Values\ContractPositionId;
use App\Domain\Contract\Values\ContractSalary;
use App\Domain\Contract\Values\ContractSettings;
use App\Domain\Contract\Values\ContractStartDate;
use App\Domain\Contract\Values\ContractTransportAllowance;
use App\Domain\Contract\Values\ContractUuid;
use App\Http\Requests\Contract\ContractRefreshedRequest;
use App\Models\Contract as ContractModel;

final class ContractRefreshedUseCase
{
    private $contractContract;

    public function __construct(ContractContract $contractContract)
    {
        $this->contractContract = $contractContract;
    }

    public function __invoke(ContractRefreshedRequest $request, ContractId $contractId): Contract
    {
        return $this->contractContract->update(
            new Contract(
                new ContractId($contractId->value()),
                new ContractUuid(null),
                new ContractApplicationId($request->integer('application_id')),
                new ContractPersonId($request->integer('person_id')),
                new ContractHeadquarterId($request->integer('headquarter_id')),
                new ContractContractTypeId($request->integer('contract_type_id')),
                new ContractPositionId($request->integer('position_id')),
                new ContractSalary((float) $request->get('salary')),
                new ContractTransportAllowance((float) $request->get('transport_allowance')),
                new ContractPaymentPeriodId($request->integer('payment_period_id')),
                new ContractStartDate($request->input('start_date')),
                new ContractEndDate($request->input('end_date')),
                new ContractPersonObservation($request->input('person_observation')),
                new ContractPersonSignedAt($request->input('person_signed_at')),
                new ContractPersonStatus(ContractModel::REFRESHED),
                new ContractLegalRepresentativeSignedAt($request->input('legal_representative_signed_at')),
                new ContractLegalRepresentativeStatus(ContractModel::APPROVED),
                new ContractSettings($request->input('settings')),
                new ContractFileSubtypesId($request->input('file_subtypes_id'))
            ),
            $contractId
        );
    }
}
