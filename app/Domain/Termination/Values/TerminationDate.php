<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class TerminationDate
{
    private $value;

    public function __construct(string|null $date)
    {
        $this->value = $date;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
