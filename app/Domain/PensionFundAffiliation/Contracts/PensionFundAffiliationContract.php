<?php

declare(strict_types=1);

namespace App\Domain\PensionFundAffiliation\Contracts;

use App\Domain\PensionFundAffiliation\File;
use App\Domain\PensionFundAffiliation\PensionFundAffiliation;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationFindRequest;

interface PensionFundAffiliationContract
{
    public function find(PensionFundAffiliationFindRequest $request, PensionFundAffiliationId $pensionFundAffiliationId): ?PensionFundAffiliation;

    public function store(PensionFundAffiliation $pensionFundAffiliation): PensionFundAffiliation;

    public function update(PensionFundAffiliation $pensionFundAffiliation, PensionFundAffiliationId $pensionFundAffiliationId): PensionFundAffiliation;

    public function file(File $file): File;
}
