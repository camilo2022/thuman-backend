<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Values;

final class BankCertificateAccountNumber
{
    private $value;

    public function __construct(string $accountNumber)
    {
        $this->value = $accountNumber;
    }

    public function value(): string
    {
        return $this->value;
    }
}
