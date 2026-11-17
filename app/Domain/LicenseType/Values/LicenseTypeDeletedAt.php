<?php

declare(strict_types=1);

namespace App\Domain\LicenseType\Values;

final class LicenseTypeDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
