<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryCurrency
{
    private $value;
    
    public function __construct(string $currency)
    {
        $this->value = $currency;
    }

    public function value(): string
    {
        return $this->value;
    }
}