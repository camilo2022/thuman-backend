<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantHeadquarterId
{
    private $value;

    public function __construct(int $headquarter)
    {
        $this->value = $headquarter;
    }

    public function value(): int
    {
        return $this->value;
    }
}
