<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class TerminationDecision
{
    private $value;

    public function __construct(string|null $decision)
    {
        $this->value = $decision;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
