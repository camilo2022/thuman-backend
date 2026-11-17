<?php

declare(strict_types=1);

namespace App\Domain\PersonType\Values;

final class PersonTypeItemId
{
    private $value;

    public function __construct(int $itemId)
    {
        $this->value = $itemId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
