<?php

declare(strict_types=1);

namespace App\Application\PensionFundAffiliationUseCase;

use App\Domain\PensionFundAffiliation\PensionFundAffiliation;
use App\Domain\PensionFundAffiliation\Contracts\PensionFundAffiliationContract;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationFindRequest;

final class PensionFundAffiliationFindUseCase
{
    private $pensionFundAffiliationContract;

    public function __construct(PensionFundAffiliationContract $pensionFundAffiliationContract)
    {
        $this->pensionFundAffiliationContract = $pensionFundAffiliationContract;
    }

    public function __invoke(PensionFundAffiliationFindRequest $request, PensionFundAffiliationId $pensionFundAffiliationId): ?PensionFundAffiliation
    {
        return $this->pensionFundAffiliationContract->find($request, $pensionFundAffiliationId);
    }
}
