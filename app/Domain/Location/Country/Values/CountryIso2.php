<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryIso2
{
    private $value;
    
    public function __construct(string $iso2)
    {
        $this->value = $iso2;
    }

    public function value(): string
    {
        return $this->value;
    }
}