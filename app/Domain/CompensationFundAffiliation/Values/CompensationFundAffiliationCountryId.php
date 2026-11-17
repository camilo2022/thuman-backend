<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation\Values;

final class CompensationFundAffiliationCountryId
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
