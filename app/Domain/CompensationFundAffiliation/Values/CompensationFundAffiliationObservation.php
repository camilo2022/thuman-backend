<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation\Values;

final class CompensationFundAffiliationObservation
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
