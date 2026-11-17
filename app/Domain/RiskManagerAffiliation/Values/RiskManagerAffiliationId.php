<?php

declare(strict_types=1);

namespace App\Domain\RiskManagerAffiliation\Values;

final class RiskManagerAffiliationId
{
    private $value;

    public function __construct(int|null $id = null)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
