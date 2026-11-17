<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryNumericCode
{
    private $value;
    
    public function __construct(string $numericCode)
    {
        $this->value = $numericCode;
    }

    public function value(): string
    {
        return $this->value;
    }
}