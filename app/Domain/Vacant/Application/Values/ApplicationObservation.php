<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class ApplicationObservation
{
    private $value;

    public function __construct(string|null $observation)
    {
        $this->value = $observation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
