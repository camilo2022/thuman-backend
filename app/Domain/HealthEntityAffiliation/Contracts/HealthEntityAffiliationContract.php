<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityAffiliation\Contracts;

use App\Domain\HealthEntityAffiliation\File;
use App\Domain\HealthEntityAffiliation\HealthEntityAffiliation;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationFindRequest;

interface HealthEntityAffiliationContract
{
    public function find(HealthEntityAffiliationFindRequest $request, HealthEntityAffiliationId $healthEntityAffiliationId): ?HealthEntityAffiliation;

    public function store(HealthEntityAffiliation $healthEntityAffiliation): HealthEntityAffiliation;

    public function update(HealthEntityAffiliation $healthEntityAffiliation, HealthEntityAffiliationId $healthEntityAffiliationId): HealthEntityAffiliation;

    public function file(File $file): File;
}
