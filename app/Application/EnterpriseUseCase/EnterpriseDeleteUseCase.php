<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase;

use App\Domain\Enterprise\Enterprise;
use App\Domain\Enterprise\Contracts\EnterpriseContract;
use App\Domain\Enterprise\Values\EnterpriseId;

final class EnterpriseDeleteUseCase
{
    private $enterpriseontract;

    public function __construct(EnterpriseContract $enterpriseontract)
    {
        $this->enterpriseontract = $enterpriseontract;
    }

    public function __invoke(EnterpriseId $personId): ?Enterprise
    {
        return $this->enterpriseontract->delete($personId);
    }
}
