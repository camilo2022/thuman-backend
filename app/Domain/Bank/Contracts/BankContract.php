<?php

declare(strict_types=1);

namespace App\Domain\Bank\Contracts;

use App\Domain\Bank\Bank;
use App\Domain\Bank\Values\BankId;
use App\Http\Requests\Bank\BankAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface BankContract
{
    public function all(string|null $search, int|null $perPage, BankAllRequest $request): LengthAwarePaginator;

    public function find(BankId $bankId): ?Bank;

    public function store(Bank $bank): Bank;

    public function update(Bank $bank, BankId $bankId): Bank;

    public function toggle(Bank $bank, BankId $bankId): Bank;

    public function delete(BankId $bankId): Bank;

    public function restore(BankId $bankId): Bank;
}
