<?php

declare(strict_types=1);

namespace App\Domain\RiskManagerAffiliation\Contracts;

use App\Domain\RiskManagerAffiliation\File;
use App\Domain\RiskManagerAffiliation\RiskManagerAffiliation;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationId;
use App\Http\Requests\RiskManagerAffiliation\RiskManagerAffiliationFindRequest;

interface RiskManagerAffiliationContract
{
    public function find(RiskManagerAffiliationFindRequest $request, RiskManagerAffiliationId $riskManagerAffiliationId): ?RiskManagerAffiliation;

    public function store(RiskManagerAffiliation $riskManagerAffiliation): RiskManagerAffiliation;

    public function update(RiskManagerAffiliation $riskManagerAffiliation, RiskManagerAffiliationId $riskManagerAffiliationId): RiskManagerAffiliation;

    public function file(File $file): File;
}
