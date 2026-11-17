<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryEmoji
{
    private $value;
    
    public function __construct(string $emoji)
    {
        $this->value = $emoji;
    }

    public function value(): string
    {
        return $this->value;
    }
}