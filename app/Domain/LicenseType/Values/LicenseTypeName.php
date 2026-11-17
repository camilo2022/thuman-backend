<?php

declare(strict_types=1);

namespace App\Domain\LicenseType\Values;

final class LicenseTypeName
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
