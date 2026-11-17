<?php

declare(strict_types=1);

namespace App\Application\CompensationFundUseCase;

use App\Domain\CompensationFund\CompensationFund;
use App\Domain\CompensationFund\Contracts\CompensationFundContract;
use App\Domain\CompensationFund\Values\CompensationFundId;

final class CompensationFundRestoreUseCase
{
    private $compensationFundContract;

    public function __construct(CompensationFundContract $compensationFundContract)
    {
        $this->compensationFundContract = $compensationFundContract;
    }

    public function __invoke(CompensationFundId $compensationFundId): ?CompensationFund
    {
        return $this->compensationFundContract->restore($compensationFundId);
    }
}
