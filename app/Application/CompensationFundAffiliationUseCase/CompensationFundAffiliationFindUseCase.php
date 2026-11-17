<?php

declare(strict_types=1);

namespace App\Application\CompensationFundAffiliationUseCase;

use App\Domain\CompensationFundAffiliation\CompensationFundAffiliation;
use App\Domain\CompensationFundAffiliation\Contracts\CompensationFundAffiliationContract;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationFindRequest;

final class CompensationFundAffiliationFindUseCase
{
    private $compensationFundAffiliationContract;

    public function __construct(CompensationFundAffiliationContract $compensationFundAffiliationContract)
    {
        $this->compensationFundAffiliationContract = $compensationFundAffiliationContract;
    }

    public function __invoke(CompensationFundAffiliationFindRequest $request, CompensationFundAffiliationId $compensationFundAffiliationId): ?CompensationFundAffiliation
    {
        return $this->compensationFundAffiliationContract->find($request, $compensationFundAffiliationId);
    }
}
