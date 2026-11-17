<?php

declare(strict_types=1);

namespace App\Domain\RiskManagerAffiliation\Values;

final class RiskManagerAffiliationUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
