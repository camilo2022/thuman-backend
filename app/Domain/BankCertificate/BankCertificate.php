<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate;

use App\Domain\BankCertificate\Values\BankCertificateAccountNumber;
use App\Domain\BankCertificate\Values\BankCertificateBankAccountTypeId;
use App\Domain\BankCertificate\Values\BankCertificateCountryId;
use App\Domain\BankCertificate\Values\BankCertificateCreatedAt;
use App\Domain\BankCertificate\Values\BankCertificateBankId;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Domain\BankCertificate\Values\BankCertificateObservation;
use App\Domain\BankCertificate\Values\BankCertificatePersonId;
use App\Domain\BankCertificate\Values\BankCertificateRelationships;
use App\Domain\BankCertificate\Values\BankCertificateStatus;
use App\Domain\BankCertificate\Values\BankCertificateUpdatedAt;

final class BankCertificate
{
    private $id;
    private $personId;
    private $countryId;
    private $bankId;
    private $bankAccountTypeId;
    private $accountNumber;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?BankCertificateId $id,
        BankCertificatePersonId $personId,
        BankCertificateCountryId $countryId,
        BankCertificateBankId $bankId,
        BankCertificateBankAccountTypeId $bankAccountTypeId,
        BankCertificateAccountNumber $accountNumber,
        BankCertificateObservation $observation,
        BankCertificateStatus $status,
        ?BankCertificateCreatedAt $createdAt = null,
        ?BankCertificateUpdatedAt $updatedAt = null,
        ?BankCertificateRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->countryId = $countryId;
        $this->bankId = $bankId;
        $this->bankAccountTypeId = $bankAccountTypeId;
        $this->accountNumber = $accountNumber;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?BankCertificateId
    {
        return $this->id;
    }

    public function personId(): BankCertificatePersonId
    {
        return $this->personId;
    }

    public function countryId(): BankCertificateCountryId
    {
        return $this->countryId;
    }

    public function bankId(): BankCertificateBankId
    {
        return $this->bankId;
    }

    public function bankAccountTypeId(): BankCertificateBankAccountTypeId
    {
        return $this->bankAccountTypeId;
    }

    public function accountNumber(): BankCertificateAccountNumber
    {
        return $this->accountNumber;
    }

    public function observation(): BankCertificateObservation
    {
        return $this->observation;
    }

    public function status(): BankCertificateStatus
    {
        return $this->status;
    }

    public function createdAt(): ?BankCertificateCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?BankCertificateUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?BankCertificateRelationships
    {
        return $this->relationships;
    }
}
