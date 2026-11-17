<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Values;

final class BankCertificateCountryId
{
    private $value;

    public function __construct(int $countryId)
    {
        $this->value = $countryId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
