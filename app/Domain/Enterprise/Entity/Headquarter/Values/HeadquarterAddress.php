<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Headquarter\Values;

final class HeadquarterAddress
{
    private $value;

    public function __construct(string $address)
    {
        $this->value = $address;
    }

    public function value(): string
    {
        return $this->value;
    }
}
