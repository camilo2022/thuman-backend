<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Headquarter\Values;

final class HeadquarterName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
