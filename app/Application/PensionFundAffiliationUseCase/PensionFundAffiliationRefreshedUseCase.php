<?php

declare(strict_types=1);

namespace App\Application\PensionFundAffiliationUseCase;

use App\Domain\PensionFundAffiliation\Contracts\PensionFundAffiliationContract;
use App\Domain\PensionFundAffiliation\PensionFundAffiliation;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationCountryId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPensionFundId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationObservation;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPersonId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationStatus;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationRefreshedRequest;
use App\Models\PensionFundAffiliation as PensionFundAffiliationModel;

final class PensionFundAffiliationRefreshedUseCase
{
    private $pensionFundAffiliationContract;

    public function __construct(PensionFundAffiliationContract $pensionFundAffiliationContract)
    {
        $this->pensionFundAffiliationContract = $pensionFundAffiliationContract;
    }

    public function __invoke(PensionFundAffiliationRefreshedRequest $request, PensionFundAffiliationId $pensionFundAffiliationId): PensionFundAffiliation
    {
        return $this->pensionFundAffiliationContract->update(
            new PensionFundAffiliation(
                new PensionFundAffiliationId($pensionFundAffiliationId->value()),
                new PensionFundAffiliationPersonId($request->integer('person_id')),
                new PensionFundAffiliationCountryId($request->integer('country_id')),
                new PensionFundAffiliationPensionFundId($request->integer('pension_fund_id')),
                new PensionFundAffiliationObservation($request->input('observation')),
                new PensionFundAffiliationStatus(PensionFundAffiliationModel::REFRESHED)
            ),
            $pensionFundAffiliationId
        );
    }
}
