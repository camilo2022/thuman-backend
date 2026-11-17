<?php

declare(strict_types=1);

namespace App\Domain\License\Values;

final class LicenseStartDate
{
    private $value;

    public function __construct(string $startDate)
    {
        $this->value = $startDate;
    }

    public function value(): string
    {
        return $this->value;
    }
}
