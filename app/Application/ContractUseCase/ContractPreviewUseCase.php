<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase;

use App\Domain\Contract\Contracts\ContractContract;
use App\Domain\Contract\Values\ContractId;

final class ContractPreviewUseCase
{
    private $contractContract;

    public function __construct(ContractContract $contractContract)
    {
        $this->contractContract = $contractContract;
    }

    public function __invoke(ContractId $contractId)
    {
        return $this->contractContract->preview($contractId);
    }
}
