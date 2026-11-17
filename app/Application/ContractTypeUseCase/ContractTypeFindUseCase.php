<?php

declare(strict_types=1);

namespace App\Application\ContractTypeUseCase;

use App\Domain\ContractType\ContractType;
use App\Domain\ContractType\Contracts\ContractTypeContract;
use App\Domain\ContractType\Values\ContractTypeId;

final class ContractTypeFindUseCase
{
    private $contractTypeContract;

    public function __construct(ContractTypeContract $contractTypeContract)
    {
        $this->contractTypeContract = $contractTypeContract;
    }

    public function __invoke(ContractTypeId $contractTypeId): ?ContractType
    {
        return $this->contractTypeContract->find($contractTypeId);
    }
}
