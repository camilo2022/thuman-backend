<?php

declare(strict_types=1);

namespace App\Domain\Profile\EmergencyContact\Values;

final class EmergencyContactDialingCountryId
{
    private $value;

    public function __construct(int $dialingCountryId)
    {
        $this->value = $dialingCountryId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
