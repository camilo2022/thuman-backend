<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonCityId
{
    private $value;
    
    public function __construct(int|null $cityId)
    {
        $this->value = $cityId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}