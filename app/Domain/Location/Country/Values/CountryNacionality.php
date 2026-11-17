<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryNacionality
{
    private $value;
    
    public function __construct(string $nacionality)
    {
        $this->value = $nacionality;
    }

    public function value(): string
    {
        return $this->value;
    }
}