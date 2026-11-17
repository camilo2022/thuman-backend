<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\CriminalRecord\Values;

final class CriminalRecordObservation
{
    private $value;

    public function __construct(string $observation)
    {
        $this->value = $observation;
    }

    public function value(): string
    {
        return $this->value;
    }
}
