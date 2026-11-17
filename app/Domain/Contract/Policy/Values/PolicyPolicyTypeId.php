<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyPolicyTypeId
{
    private $value;

    public function __construct(int $policyTypeId)
    {
        $this->value = $policyTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
