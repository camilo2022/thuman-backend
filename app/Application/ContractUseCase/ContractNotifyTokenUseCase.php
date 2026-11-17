<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase;

use App\Domain\Contract\Contract;
use App\Domain\Contract\Contracts\ContractContract;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractNotifyTokenRequest;

final class ContractNotifyTokenUseCase
{
    private $contractContract;

    public function __construct(ContractContract $contractContract)
    {
        $this->contractContract = $contractContract;
    }

    public function __invoke(ContractNotifyTokenRequest $request, ContractId $contractId): ?Contract
    {
        return $this->contractContract->notifyToken($request, $contractId);
    }
}
