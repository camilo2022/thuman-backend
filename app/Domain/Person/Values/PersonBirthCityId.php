<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonBirthCityId
{
    private $value;

    public function __construct(int|null $birthCityId)
    {
        $this->value = $birthCityId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
