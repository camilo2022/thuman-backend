<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase;

use App\Domain\Enterprise\Enterprise;
use App\Domain\Enterprise\Contracts\EnterpriseContract;
use App\Domain\Enterprise\Values\EnterpriseId;

final class EnterpriseFindUseCase
{
    private $enterpriseContract;

    public function __construct(EnterpriseContract $enterpriseContract)
    {
        $this->enterpriseContract = $enterpriseContract;
    }

    public function __invoke(EnterpriseId $enterpriseId): ?Enterprise
    {
        return $this->enterpriseContract->find($enterpriseId);
    }
}
