<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryIso3
{
    private $value;
    
    public function __construct(string $iso3)
    {
        $this->value = $iso3;
    }

    public function value(): string
    {
        return $this->value;
    }
}