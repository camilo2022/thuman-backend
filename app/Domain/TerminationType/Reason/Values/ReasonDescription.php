<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Reason\Values;

final class ReasonDescription
{
    private $value;

    public function __construct(string|null $description)
    {
        $this->value = $description;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
