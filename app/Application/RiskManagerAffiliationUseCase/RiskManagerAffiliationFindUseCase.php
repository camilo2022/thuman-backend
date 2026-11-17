<?php

declare(strict_types=1);

namespace App\Application\RiskManagerAffiliationUseCase;

use App\Domain\RiskManagerAffiliation\RiskManagerAffiliation;
use App\Domain\RiskManagerAffiliation\Contracts\RiskManagerAffiliationContract;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationId;
use App\Http\Requests\RiskManagerAffiliation\RiskManagerAffiliationFindRequest;

final class RiskManagerAffiliationFindUseCase
{
    private $riskManagerAffiliationContract;

    public function __construct(RiskManagerAffiliationContract $riskManagerAffiliationContract)
    {
        $this->riskManagerAffiliationContract = $riskManagerAffiliationContract;
    }

    public function __invoke(RiskManagerAffiliationFindRequest $request, RiskManagerAffiliationId $riskManagerAffiliationId): ?RiskManagerAffiliation
    {
        return $this->riskManagerAffiliationContract->find($request, $riskManagerAffiliationId);
    }
}
