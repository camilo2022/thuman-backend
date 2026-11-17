<?php

declare(strict_types=1);

namespace App\Application\CompensationFundAffiliationUseCase;

use App\Domain\CompensationFundAffiliation\Contracts\CompensationFundAffiliationContract;
use App\Domain\CompensationFundAffiliation\CompensationFundAffiliation;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCountryId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCompensationFundId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationObservation;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationPersonId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationStatus;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationApprovedRequest;
use App\Models\CompensationFundAffiliation as CompensationFundAffiliationModel;

final class CompensationFundAffiliationApprovedUseCase
{
    private $compensationFundAffiliationContract;

    public function __construct(CompensationFundAffiliationContract $compensationFundAffiliationContract)
    {
        $this->compensationFundAffiliationContract = $compensationFundAffiliationContract;
    }

    public function __invoke(CompensationFundAffiliationApprovedRequest $request, CompensationFundAffiliationId $compensationFundAffiliationId): CompensationFundAffiliation
    {
        return $this->compensationFundAffiliationContract->update(
            new CompensationFundAffiliation(
                new CompensationFundAffiliationId($compensationFundAffiliationId->value()),
                new CompensationFundAffiliationPersonId($request->integer('person_id')),
                new CompensationFundAffiliationCountryId($request->integer('country_id')),
                new CompensationFundAffiliationCompensationFundId($request->integer('compensation_fund_id')),
                new CompensationFundAffiliationObservation($request->input('observation')),
                new CompensationFundAffiliationStatus(CompensationFundAffiliationModel::APPROVED)
            ),
            $compensationFundAffiliationId
        );
    }
}
