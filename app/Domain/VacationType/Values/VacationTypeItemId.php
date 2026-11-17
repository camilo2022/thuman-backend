<?php

declare(strict_types=1);

namespace App\Domain\VacationType\Values;

final class VacationTypeItemId
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
