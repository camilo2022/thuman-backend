<?php

declare(strict_types=1);

namespace App\Domain\LicenseType\Values;

final class LicenseTypeCreatedAt
{
    private $value;

    public function __construct(string|null $createdAt)
    {
        $this->value = $createdAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
