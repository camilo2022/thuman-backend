<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocumentCertificate\Values;

final class FiscalDocumentCertificateRelationships
{
    private ?object $person;
    private ?object $country;
    private ?object $fiscal_document;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $country = null,
        ?object $fiscal_document = null,
        ?object $file = null
    ) {
        $this->person = $person;
        $this->country = $country;
        $this->fiscal_document = $fiscal_document;
        $this->file = $file;
    }

    public function person(): ?array
    {
        return $this->person;
    }

    public function country(): ?array
    {
        return $this->country;
    }

    public function fiscalDocument(): ?array
    {
        return $this->fiscal_document;
    }

    public function file(): ?array
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'country' => $this->country,
            'fiscal_document' => $this->fiscal_document,
            'file' => $this->file
        ];
    }
}
