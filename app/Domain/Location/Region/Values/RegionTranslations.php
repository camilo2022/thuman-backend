<?php

declare(strict_types=1);

namespace App\Domain\Location\Region\Values;

final class RegionTranslations
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
