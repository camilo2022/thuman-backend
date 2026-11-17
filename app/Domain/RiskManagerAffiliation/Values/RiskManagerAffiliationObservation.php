<?php

declare(strict_types=1);

namespace App\Domain\RiskManagerAffiliation\Values;

final class RiskManagerAffiliationObservation
{
    private $value;

    public function __construct(string|null $observation)
    {
        $this->value = $observation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
