<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityAffiliation\Values;

final class HealthEntityAffiliationHealthEntityId
{
    private $value;

    public function __construct(int $healthEntityId)
    {
        $this->value = $healthEntityId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
