<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Reason\Values;

final class ReasonName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
