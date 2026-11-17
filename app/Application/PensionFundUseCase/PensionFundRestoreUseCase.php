<?php

declare(strict_types=1);

namespace App\Application\PensionFundUseCase;

use App\Domain\PensionFund\PensionFund;
use App\Domain\PensionFund\Contracts\PensionFundContract;
use App\Domain\PensionFund\Values\PensionFundId;

final class PensionFundRestoreUseCase
{
    private $pensionFundContract;

    public function __construct(PensionFundContract $pensionFundContract)
    {
        $this->pensionFundContract = $pensionFundContract;
    }

    public function __invoke(PensionFundId $pensionFundId): ?PensionFund
    {
        return $this->pensionFundContract->restore($pensionFundId);
    }
}
