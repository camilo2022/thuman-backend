<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryNative
{
    private $value;
    
    public function __construct(string|null $native)
    {
        $this->value = $native;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}