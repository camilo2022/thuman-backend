<?php

declare(strict_types=1);

namespace App\Domain\VacationType\Values;

final class VacationTypeName
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
