<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractPersonObservation
{
    private $value;

    public function __construct(string|null $personObservation)
    {
        $this->value = $personObservation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
