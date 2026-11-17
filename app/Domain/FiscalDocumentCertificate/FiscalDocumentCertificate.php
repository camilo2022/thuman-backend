<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocumentCertificate;

use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateCountryId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateCreatedAt;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateFiscalDocumentId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateObservation;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificatePersonId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateRelationships;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateStatus;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateUpdatedAt;

final class FiscalDocumentCertificate
{
    private $id;
    private $personId;
    private $countryId;
    private $fiscalDocumentId;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?FiscalDocumentCertificateId $id,
        FiscalDocumentCertificatePersonId $personId,
        FiscalDocumentCertificateCountryId $countryId,
        FiscalDocumentCertificateFiscalDocumentId $fiscalDocumentId,
        FiscalDocumentCertificateObservation $observation,
        FiscalDocumentCertificateStatus $status,
        ?FiscalDocumentCertificateCreatedAt $createdAt = null,
        ?FiscalDocumentCertificateUpdatedAt $updatedAt = null,
        ?FiscalDocumentCertificateRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->countryId = $countryId;
        $this->fiscalDocumentId = $fiscalDocumentId;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?FiscalDocumentCertificateId
    {
        return $this->id;
    }

    public function personId(): FiscalDocumentCertificatePersonId
    {
        return $this->personId;
    }

    public function countryId(): FiscalDocumentCertificateCountryId
    {
        return $this->countryId;
    }

    public function fiscalDocumentId(): FiscalDocumentCertificateFiscalDocumentId
    {
        return $this->fiscalDocumentId;
    }

    public function observation(): FiscalDocumentCertificateObservation
    {
        return $this->observation;
    }

    public function status(): FiscalDocumentCertificateStatus
    {
        return $this->status;
    }

    public function createdAt(): ?FiscalDocumentCertificateCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FiscalDocumentCertificateUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?FiscalDocumentCertificateRelationships
    {
        return $this->relationships;
    }
}
