<?php

declare(strict_types=1);

namespace App\Domain\License\Values;

final class LicenseLicenseTypeId
{
    private $value;

    public function __construct(int $licenseTypeId)
    {
        $this->value = $licenseTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
