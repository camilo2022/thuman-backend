<?php

declare(strict_types=1);

namespace App\Application\PolicyTypeUseCase;

use App\Domain\PolicyType\PolicyType;
use App\Domain\PolicyType\Contracts\PolicyTypeContract;
use App\Domain\PolicyType\Values\PolicyTypeId;

final class PolicyTypeFindUseCase
{
    private $policyTypeContract;

    public function __construct(PolicyTypeContract $policyTypeContract)
    {
        $this->policyTypeContract = $policyTypeContract;
    }

    public function __invoke(PolicyTypeId $policyTypeId): ?PolicyType
    {
        return $this->policyTypeContract->find($policyTypeId);
    }
}
