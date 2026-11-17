<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryCurrencySymbol
{
    private $value;
    
    public function __construct(string $currencySymbol)
    {
        $this->value = $currencySymbol;
    }

    public function value(): string
    {
        return $this->value;
    }
}