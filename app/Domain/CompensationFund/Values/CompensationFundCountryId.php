<?php

declare(strict_types=1);

namespace App\Domain\CompensationFund\Values;

final class CompensationFundCountryId
{
    private $value;

    public function __construct(int|null $countryId = null)
    {
        $this->value = $countryId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
