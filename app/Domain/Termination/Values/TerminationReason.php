<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class TerminationReason
{
    private $value;

    public function __construct(string|null $reason)
    {
        $this->value = $reason;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
