<?php

declare(strict_types=1);

namespace App\Domain\Profile\EmergencyContact\Values;

final class EmergencyContactFirstLastName
{
    private $value;

    public function __construct(string $firstLastName)
    {
        $this->value = $firstLastName;
    }

    public function value(): string
    {
        return $this->value;
    }
}
