<?php

declare(strict_types=1);

namespace App\Domain\Profile\EmergencyContact\Values;

final class EmergencyContactSecondName
{
    private $value;

    public function __construct(string|null $secondName)
    {
        $this->value = $secondName;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
