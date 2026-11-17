<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocumentCertificate\Values;

final class FiscalDocumentCertificateFiscalDocumentId
{
    private $value;

    public function __construct(int $fiscalDocumentId)
    {
        $this->value = $fiscalDocumentId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
