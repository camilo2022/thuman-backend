<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Values;

final class BankCertificateBankId
{
    private $value;

    public function __construct(int $bankId)
    {
        $this->value = $bankId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
