<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumPersonObservation
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
