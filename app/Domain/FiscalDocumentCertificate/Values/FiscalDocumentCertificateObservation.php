<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocumentCertificate\Values;

final class FiscalDocumentCertificateObservation
{
    private $value;

    public function __construct(string|null $observation)
    {
        $this->value = $observation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
