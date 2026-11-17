<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Values;

final class BankCertificateStatus
{
    private $value;

    public function __construct(string|null $status)
    {
        $this->value = $status;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
