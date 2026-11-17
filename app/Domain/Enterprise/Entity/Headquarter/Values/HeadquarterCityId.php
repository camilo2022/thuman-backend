<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Headquarter\Values;

final class HeadquarterCityId
{
    private $value;

    public function __construct(int $cityId)
    {
        $this->value = $cityId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
