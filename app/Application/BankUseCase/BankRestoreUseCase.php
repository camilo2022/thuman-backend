<?php

declare(strict_types=1);

namespace App\Application\BankUseCase;

use App\Domain\Bank\Bank;
use App\Domain\Bank\Contracts\BankContract;
use App\Domain\Bank\Values\BankId;

final class BankRestoreUseCase
{
    private $bankContract;

    public function __construct(BankContract $bankContract)
    {
        $this->bankContract = $bankContract;
    }

    public function __invoke(BankId $bankId): ?Bank
    {
        return $this->bankContract->restore($bankId);
    }
}
