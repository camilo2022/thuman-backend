<?php

declare(strict_types=1);

namespace App\Domain\BankAccountType\Contracts;

use App\Domain\BankAccountType\BankAccountType;
use App\Domain\BankAccountType\Values\BankAccountTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface BankAccountTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(BankAccountTypeId $bankAccountTypeId): ?BankAccountType;
}
