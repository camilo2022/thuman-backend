<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryPhoneCode
{
    private $value;
    
    public function __construct(string $phoneCode)
    {
        $this->value = $phoneCode;
    }

    public function value(): string
    {
        return $this->value;
    }
}