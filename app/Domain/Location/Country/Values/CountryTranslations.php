<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountryTranslations
{
    private $value;

    public function __construct(object|array $translations)
    {
        $this->value = $translations;
    }

    public function value(): object|array
    {
        return $this->value;
    }

}
