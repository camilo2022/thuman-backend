<?php

declare(strict_types=1);

namespace App\Application\RiskManagerUseCase;

use App\Domain\RiskManager\RiskManager;
use App\Domain\RiskManager\Contracts\RiskManagerContract;
use App\Domain\RiskManager\Values\RiskManagerId;

final class RiskManagerFindUseCase
{
    private $riskManagerContract;

    public function __construct(RiskManagerContract $riskManagerContract)
    {
        $this->riskManagerContract = $riskManagerContract;
    }

    public function __invoke(RiskManagerId $riskManagerId): ?RiskManager
    {
        return $this->riskManagerContract->find($riskManagerId);
    }
}
