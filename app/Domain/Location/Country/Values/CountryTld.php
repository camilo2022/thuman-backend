<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryTld
{
    private $value;
    
    public function __construct(string $tld)
    {
        $this->value = $tld;
    }

    public function value(): string
    {
        return $this->value;
    }
}