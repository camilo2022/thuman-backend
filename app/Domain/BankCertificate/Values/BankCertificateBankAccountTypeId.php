<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Values;

final class BankCertificateBankAccountTypeId
{
    private $value;

    public function __construct(int $bankAccountTypeId)
    {
        $this->value = $bankAccountTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
