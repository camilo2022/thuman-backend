<?php

declare(strict_types=1);

namespace App\Domain\EntityType\Values;

final class EntityTypeCountryId
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
