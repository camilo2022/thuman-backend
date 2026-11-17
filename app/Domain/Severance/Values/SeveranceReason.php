<?php

declare(strict_types=1);

namespace App\Domain\Severance\Values;

final class SeveranceReason
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
