<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation\Contracts;

use App\Domain\CompensationFundAffiliation\File;
use App\Domain\CompensationFundAffiliation\CompensationFundAffiliation;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationFindRequest;

interface CompensationFundAffiliationContract
{
    public function find(CompensationFundAffiliationFindRequest $request, CompensationFundAffiliationId $compensationFundAffiliationId): ?CompensationFundAffiliation;

    public function store(CompensationFundAffiliation $compensationFundAffiliation): CompensationFundAffiliation;

    public function update(CompensationFundAffiliation $compensationFundAffiliation, CompensationFundAffiliationId $compensationFundAffiliationId): CompensationFundAffiliation;

    public function file(File $file): File;
}
