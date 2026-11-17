<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantQuantity
{
    private $value;

    public function __construct(int $quantity)
    {
        $this->value = $quantity;
    }

    public function value(): int
    {
        return $this->value;
    }
}
