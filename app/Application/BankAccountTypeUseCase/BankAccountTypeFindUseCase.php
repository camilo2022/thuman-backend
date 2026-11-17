<?php

declare(strict_types=1);

namespace App\Application\BankAccountTypeUseCase;

use App\Domain\BankAccountType\BankAccountType;
use App\Domain\BankAccountType\Contracts\BankAccountTypeContract;
use App\Domain\BankAccountType\Values\BankAccountTypeId;

final class BankAccountTypeFindUseCase
{
    private $bankAccountTypeContract;

    public function __construct(BankAccountTypeContract $bankAccountTypeContract)
    {
        $this->bankAccountTypeContract = $bankAccountTypeContract;
    }

    public function __invoke(BankAccountTypeId $bankAccountTypeId): ?BankAccountType
    {
        return $this->bankAccountTypeContract->find($bankAccountTypeId);
    }
}
