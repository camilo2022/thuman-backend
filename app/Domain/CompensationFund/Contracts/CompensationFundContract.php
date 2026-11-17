<?php

declare(strict_types=1);

namespace App\Domain\CompensationFund\Contracts;

use App\Domain\CompensationFund\CompensationFund;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Http\Requests\CompensationFund\CompensationFundAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompensationFundContract
{
    public function all(string|null $search, int|null $perPage, CompensationFundAllRequest $request): LengthAwarePaginator;

    public function find(CompensationFundId $compensationFundId): ?CompensationFund;

    public function store(CompensationFund $compensationFund): CompensationFund;

    public function update(CompensationFund $compensationFund, CompensationFundId $compensationFundId): CompensationFund;

    public function toggle(CompensationFund $compensationFund, CompensationFundId $compensationFundId): CompensationFund;

    public function delete(CompensationFundId $compensationFundId): CompensationFund;

    public function restore(CompensationFundId $compensationFundId): CompensationFund;
}
