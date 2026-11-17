<?php

declare(strict_types=1);

namespace App\Application\HealthEntityAffiliationUseCase;

use App\Domain\HealthEntityAffiliation\HealthEntityAffiliation;
use App\Domain\HealthEntityAffiliation\Contracts\HealthEntityAffiliationContract;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationFindRequest;

final class HealthEntityAffiliationFindUseCase
{
    private $healthEntityAffiliationContract;

    public function __construct(HealthEntityAffiliationContract $healthEntityAffiliationContract)
    {
        $this->healthEntityAffiliationContract = $healthEntityAffiliationContract;
    }

    public function __invoke(HealthEntityAffiliationFindRequest $request, HealthEntityAffiliationId $healthEntityAffiliationId): ?HealthEntityAffiliation
    {
        return $this->healthEntityAffiliationContract->find($request, $healthEntityAffiliationId);
    }
}
