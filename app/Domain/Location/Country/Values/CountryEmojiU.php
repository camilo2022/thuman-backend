<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryEmojiU
{
    private $value;
    
    public function __construct(string $emojiU)
    {
        $this->value = $emojiU;
    }

    public function value(): string
    {
        return $this->value;
    }
}