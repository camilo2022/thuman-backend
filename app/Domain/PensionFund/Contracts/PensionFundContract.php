<?php

declare(strict_types=1);

namespace App\Domain\PensionFund\Contracts;

use App\Domain\PensionFund\PensionFund;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Http\Requests\PensionFund\PensionFundAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface PensionFundContract
{
    public function all(string|null $search, int|null $perPage, PensionFundAllRequest $request): LengthAwarePaginator;

    public function find(PensionFundId $pensionFundId): ?PensionFund;

    public function store(PensionFund $pensionFund): PensionFund;

    public function update(PensionFund $pensionFund, PensionFundId $pensionFundId): PensionFund;

    public function toggle(PensionFund $pensionFund, PensionFundId $pensionFundId): PensionFund;

    public function delete(PensionFundId $pensionFundId): PensionFund;

    public function restore(PensionFundId $pensionFundId): PensionFund;
}
