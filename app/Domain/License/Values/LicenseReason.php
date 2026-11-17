<?php

declare(strict_types=1);

namespace App\Domain\License\Values;

final class LicenseReason
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
