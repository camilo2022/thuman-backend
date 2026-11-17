<?php

declare(strict_types=1);

namespace App\Domain\HealthEntity\Values;

final class HealthEntityCountryId
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
