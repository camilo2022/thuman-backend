<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityAffiliation\Values;

final class HealthEntityAffiliationCountryId
{
    private $value;

    public function __construct(int $countryId)
    {
        $this->value = $countryId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
