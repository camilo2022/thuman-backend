<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryCurrencyName
{
    private $value;
    
    public function __construct(string $currencyName)
    {
        $this->value = $currencyName;
    }

    public function value(): string
    {
        return $this->value;
    }
}